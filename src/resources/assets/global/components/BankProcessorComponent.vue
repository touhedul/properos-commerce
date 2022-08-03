<template>
    <div>
        <div v-show="payment_method != 'profile'" class="row">
            <div class="col-md-8 offset-md-2">
                <!--                 <div v-if="bus.current_payment_profiles.length > 0" class="row">
                    <div class="col-md-12">
                        <div style="padding-top: 10px; padding-bottom: 10px; font-size: 12px">Pay with a new bank account</div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-6">
                        <label>
                            <b>Full name on account</b>
                        </label>
                        <div class="input-group">
                            <input v-model="bankInfo.name" type="text" class="form-control" :class="{'input-error-select' : error.name}">
                        </div>
                        <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <b>Account number</b>
                        </label>
                        <div class="input-group">
                            <input v-model="bankInfo.account_number" type="text" class="form-control" :class="{'input-error-select' : error.account_number}">
                        </div>
                        <span v-if="error.account_number" class="message-error">{{error_message.account_number}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-6">
                        <label>
                            <b>Routing number</b>
                        </label>
                        <div class="input-group">
                            <input v-model="bankInfo.routing_number" type="text" class="form-control" :class="{'input-error-select' : error.routing_number}">
                        </div>
                        <span v-if="error.routing_number" class="message-error">{{error_message.routing_number}}</span>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <b>Account</b>
                        </label>
                        <div class="input-group">
                            <select v-model="bankInfo.account_type" class="form-control" :class="{'input-error-select' : error.account_type}">
                                <option v-for="(type, index) in account_types" :value="type.name">{{type.label}}</option>
                            </select>
                        </div>
                        <span v-if="error.account_type" class="message-error">{{error_message.account_type}}</span>
                    </div>
                </div>
                <div v-if="total_amount" class="row" style="margin-top: 10px">
                    <div class="col-md-6" style="padding-left: 5px">
                        <div class="input-group">
                            <div class="skin skin-square">
                                <span style="padding: 10px">
                                    <input id="save_method_bank" type="checkbox" name="save_method_bank">
                                    <label for="save_method_bank">
                                        Save for future use
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <!-- <div class="col-md-6" style="padding-left: 5px">
                        <div v-if="!edit_subscription" class="input-group">
                            <div class="skin skin-square">
                                <span style="padding: 10px">
                                    <input id="recurring_payments_bank" type="checkbox" name="recurring_payments_bank">
                                    <label for="recurring_payments_bank">
                                        Enroll in automatic payments
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-12 text-right">
                        <button v-if="total_amount" @click="configurePayment()" class="btn btn-primary" :disabled="(total_amount <= 0)">
                            Pay ${{total_amount}}
                        </button>
                        <button v-else @click="savePayment()" class="btn btn-primary">
                            Save
                        </button>
                        <button @click="cancelAddPaymentmethod()" class="btn btn-default">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaymentServices from '../services/PaymentServices';
    import misc from '../misc/payments.js';
    import Helpers from '../misc/helpers.js';
    export default {
        mixins: [Helpers, PaymentServices],
        props: ['user', 'card_processor', 'client_key', 'api_id', 'edit_subscription','order_id', 'total_amount'],
        data() {
            return {
                bankInfo: {
                    name: this.user.firstname + ' ' + this.user.lastname,
                    account_number: '',
                    routing_number: '',
                    account_type: '',
                    save_method: false,
                    recurring_payments: false,
                },
                payment: payments.driver(this.card_processor, 'bank'),
                //current_payment_profiles: [],
                payment_method: '',
                customer_profile: '',
                customer_payment_profile: '',
                account_types: [
                    {
                        name: 'checking',
                        label: 'Checking'
                    },
                    {
                        name: 'savings',
                        label: 'Savings'
                    }],
                error: {
                    name: false,
                    account_number: false,
                    routing_number: false,
                    account_type: false
                },
                error_message: {
                    name: '',
                    account_number: '',
                    routing_number: '',
                    account_type: ''
                }
            }
        },
        methods: {

            configurePayment() {
                if (this.validateBankAccountInfo()) {
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
                            var paymentData = {
                                clientKey: this.client_key,
                                apiLoginID: this.api_id,
                                bankInfo: this.bankInfo,
                                type: 'bank'
                            };
                            $.LoadingOverlay("show");
                            this.payment.bank.createCardToken(paymentData, this.createTokenCallback);
                        }
                    });
                }
            },
            savePayment() {
                if (this.validateBankAccountInfo()) {
                    var paymentData = {
                        clientKey: this.client_key,
                        apiLoginID: this.api_id,
                        bankInfo: this.bankInfo,
                        type: 'bank'
                    };
                    $.LoadingOverlay("show");
                    this.payment.bank.createCardToken(paymentData, this.createTokenCallback);
                }
            },
            // updateSubscriptionPayment() {
            //     if (this.validateBankAccountInfo()) {
            //         swal({
            //             title: "Are you sure?",
            //             text: "Confirm that you want to change the payment method for this payment subscription",
            //             icon: "warning",
            //             buttons: {
            //                 cancel: {
            //                     text: "Cancel",
            //                     value: null,
            //                     visible: true,
            //                     className: "btn-warning",
            //                     closeModal: true,
            //                 },
            //                 confirm: {
            //                     text: "Yes",
            //                     value: true,
            //                     visible: true,
            //                     className: "btn-primary",
            //                     closeModal: true
            //                 }
            //             }
            //         }).then(isConfirm => {
            //             if (isConfirm) {
            //                 var paymentData = {
            //                     clientKey: this.client_key,
            //                     apiLoginID: this.api_id,
            //                     bankInfo: this.bankInfo,
            //                     type: 'bank',
            //                 };
            //                 $.LoadingOverlay("show");
            //                 this.payment.bank.createCardToken(paymentData, this.createTokenCallback);
            //             }
            //         });
            //     }
            // },
            createTokenCallback(response) {
                if (response.status > 0) {
                    let data = {
                        'payment_token': response.data.token,
                        'payment_descriptor': response.data.description,
                        'customer_name': this.bankInfo.name,
                        'recurring_payments': this.bankInfo.recurring_payments,
                        'save': (!this.total_amount) ? true : this.bankInfo.save_method,
                        'type': 'bank',
                        'user_id': this.user.id,
                        'order_id': this.order_id,
                        // 'earlier_coverage': this.subscription.earlier_coverage
                    }
                    if (this.bankInfo.save_method || this.edit_subscription) {
                        data.last_digits = this.bankInfo.account_number.substr(-3);
                    }
                    if (this.edit_subscription) {
                        this.updatePaymentSubscriptionCall(data, this.configurePaymentCallback);
                    }
                    else {
                        this.addPaymentCall(data, this.configurePaymentCallback);
                        // this.configurePaymentCall(data, this.configurePaymentCallback);
                    }
                } else {
                    $.LoadingOverlay("hide");
                    var errors = '';
                    for (var i in response.errors) {
                        errors += response.errors[i].text + '.';
                    }
                    toastr.error(errors, 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                }
            },
            configurePaymentCallback(code, message, data) {
                var self = this;
                console.log(code,message,data);
                $.LoadingOverlay("hide");
               if (code == '200') {
                    var self = this;
                    toastr.success(message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.cancelAddPaymentmethod();
                } else {
                    if (Helpers.isAssoc(data)) {
                        let errors = [];
                        for (var i in data) {
                            var string
                            errors.push('<span>' + data[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(data[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            cancelAddPaymentmethod(){
                this.$emit('addPaymentMethod', false);
                this.resetBankInfo();
                if(this.total_amount){
                    $('#credit_card').iCheck('check');
                }
            },
            validateBankAccountInfo() {
                if (this.bankInfo.name != '' && this.bankInfo.account_number != ''
                    && this.bankInfo.routing_number != '' && this.bankInfo.account_type != '') {
                    return true;
                }
                else {
                    if (this.bankInfo.name == '') {
                        this.error.name = true;
                        this.error_message.name = 'Name on account is required';
                    }
                    if (this.bankInfo.account_number == '') {
                        this.error.account_number = true;
                        this.error_message.account_number = 'Account number is required';
                    }
                    if (this.bankInfo.routing_number == '') {
                        this.error.routing_number = true;
                        this.error_message.routing_number = 'Routing number is required';
                    }
                    if (this.bankInfo.account_type == '') {
                        this.error.account_type = true;
                        this.error_message.account_type = 'Account type is required';
                    }
                }
            },
            resetBankInfo() {
                this.bankInfo.name = '';
                this.bankInfo.account_number = '';
                this.bankInfo.routing_number = '';
                this.bankInfo.account_type = '';
            },
            cancelPayment() {
                swal({
                    title: "Are you sure?",
                    text: "Confirm that you want to cancel this payment",
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
                        this.resetBankInfo();
                        window.location.href = '/dashboard/subscriptions/edit-subscription/' + this.subscription.id
                    }
                });

            },
        },
        watch: {
            'bankInfo.name'(val) {
                if (val != '') {
                    this.error.name = false;
                    this.error_message.name = '';
                }
            },
            'bankInfo.account_number'(val) {
                if (val != '') {
                    this.error.account_number = false;
                    this.error_message.account_number = '';
                }
            },
            'bankInfo.routing_number'(val) {
                if (val != '') {
                    this.error.routing_number = false;
                    this.error_message.routing_number = '';
                }
            },
            'bankInfo.account_type'(val) {
                if (val != '') {
                    this.error.account_type = false;
                    this.error_message.account_type = '';
                }
            },
            'payment_method'(val) {
                if (val != 'profile') {
                    $('#payment_profile').iCheck('uncheck');
                }
            }
        },
        created() {
            if (this.card_processor.toLowerCase() == 'stripe') {
                Stripe.setPublishableKey(this.client_key);
            }
            // if (this.subscription.customer_profile) {
            //     if (this.subscription.customer_profile.payment_profiles.length > 0) {
            //         this.customer_profile = this.subscription.customer_profile.customer_profile_id;
            //         bus.current_payment_profiles = this.subscription.customer_profile.payment_profiles;
            //     }
            // }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('#recurring_payments_bank').on('ifChecked', function (event) {
                    self.bankInfo.recurring_payments = 1;
                    $('#save_method_bank').iCheck('check');
                });

                $('#recurring_payments_bank').on('ifUnchecked', function (event) {
                    self.bankInfo.recurring_payments = 0;
                    $('#save_method_bank').iCheck('uncheck');
                });
                $('#save_method_bank').on('ifChecked', function (event) {
                    self.bankInfo.save_method = 1;
                });

                $('#save_method_bank').on('ifUnchecked', function (event) {
                    self.bankInfo.save_method = 0;
                });
            });

            // if (bus.current_payment_profiles.length > 0) {
            //     for (var i in bus.current_payment_profiles) {
            //         $('#payment_profile' + i).on('ifChecked', function (event) {
            //             self.payment_method = 'profile';
            //             self.customer_payment_profile = event.target.value;
            //         });

            //         $('#payment_profile' + i).on('ifUnchecked', function (event) {
            //             self.payment_method = '';
            //             self.customer_payment_profile = '';
            //         });
            //     }
            // }
        }
    }
</script>

<style>
    .default-method {
        padding-bottom: 0px;
        background-color: #86dd861a;
        border-color: #00800066;
        border-style: dashed;
        margin-top: 5px;
    }
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