<template>
    <div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <fieldset class="form-group">
                    <label for="basicInput">Amount (cash/check/money order)</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="ft-type"></i>
                        </span>
                        <input v-model="amount" type="number" placeholder="Type amount received" class="form-control"
                            :class="{'input-error-select' : error.amount}" readonly>
                    </div>
                    <span v-if="error.amount" class="message-error">{{error_message.amount}}</span>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <fieldset class="form-group">
                    <label for="basicInput">Description (optional)</label>
                    <div class="input-group">
                        <textarea v-model="description" placeholder="Type a description..." rows="7" type="text" class="form-control"></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3 text-right">
                <button @click="setPaidWithCash()" class="btn btn-primary" :disabled="(total_amount <= 0)">
                    Pay ${{total_amount}}
                </button>
                <button @click="cancelPayment()" class="btn btn-default">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import PaymentServices from '../services/PaymentServices';
    export default {
        mixins: [PaymentServices],
        props: ['order_id', 'total_amount'],
        data() {
            return {
                amount: parseFloat(this.total_amount).toFixed(2),
                description: '',
                error: {
                    amount: false
                },
                error_message: {
                    amount: ''
                }
            }
        },
        methods: {
            setPaidWithCash() {
                if (this.amount) {
                    swal({
                        title: "Are you sure?",
                        text: "Confirm that you want to execute this payment",
                        icon: "warning",
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: null,
                                visible: true,
                                className: "btn-warning",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Yes",
                                value: true,
                                visible: true,
                                className: "btn-primary",
                                closeModal: true
                            }
                        }
                    }).then(isConfirm => {
                        if (isConfirm) {
                            let data = {
                                'id': this.order_id,
                                'amount': this.amount,
                                'payment_method_type': 'cash-check',
                                // 'earlier_coverage': this.subscription.earlier_coverage ? this.subscription.earlier_coverage : ''
                            }
                            if (this.description) {
                                data.description = this.description;
                            }
                            $.LoadingOverlay("show");
                            this.setPaidWithCashCall(data, this.setPaidWithCashCallback);
                        }
                    });
                }
                else {
                    this.error.amount = true;
                    this.error_message.amount = 'Amount received is required';
                }
            },
            setPaidWithCashCallback(response) {
                var self = this;
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.cancelPayment();
                } else {
                    if (this.isAssoc(response.data.errors)) {
                        let errors = [];
                        for (var i in response.data.errors) {
                            var string
                            errors.push('<span>' + response.data.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.data.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            cancelPayment() {
                this.$emit('addPaymentMethod', false);
                this.description = '';
                $('#credit_card').iCheck('check');
            },
        },
        watch: {
            total_amount(val) {
                this.amount = parseFloat(val).toFixed(2);
            },
            amount(val) {
                if (val) {
                    this.error.amount = false;
                    this.error_message.amount = '';
                }
            }
        },
        created() {

        },
        mounted() {
            
        },
    }
</script>

<style>
    .message-error {
        color: #d61212;
        padding-top: 10px;
        font-size: 12px;
    }
    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }
</style>