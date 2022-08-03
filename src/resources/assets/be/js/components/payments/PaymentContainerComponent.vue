<template>
    <div class="card" style="margin-bottom:0;">
        <div class="card-content">
            <div v-if="current_payment_profiles.length > 0 && !showAddPaymentMethod" class="card-header">
                <div class="row" style="text-align:right;">
                    <!-- <div class="form-actions col-xs-12 col-sm-12 col-md-12"> -->
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <a v-if="!showAddPaymentMethod" @click="showAddNewPayment()" class="btn btn-primary" style="padding: 0.75rem 1rem;">
                            <i class="fa fa-cubes"></i> New Payment
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" style="margin-top: 20px">
                    <div v-show="current_payment_profiles.length > 0 && !showAddPaymentMethod" class="col-xs-12 col-sm-12 col-md-12">
                        <ul id="payments_list" class="list-group" style="list-style: none">
                            <li v-for="(payment_profile, index) in current_payment_profiles"
                                class="list-group-item" :class="{'default-method' : payment_profile.default == 1}"
                                style="padding-bottom: 0px">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="input-group">
                                            <div class="skin skin-square">
                                                <span style="padding: 10px">
                                                    <input :class="'payment_profile'+payment_profile.id" :id="'payment_profile' + index" :value="payment_profile.payment_profile_id" :data-id="payment_profile.id"
                                                        :data-pfi="payment_profile.payment_profile_id" type="radio"
                                                        name="payment_profiles">
                                                    <label :for="'payment_profile' + index">
                                                    </label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <b>
                                            <span v-if="payment_profile.payment_method_type == 'card'">{{payment_profile.brand|capitalize}}</span> 
                                            <span v-else-if="payment_profile.payment_method_type == 'bank' && payment_profile.brand">{{payment_profile.brand|capitalize}}</span> 
                                            <span v-else-if="payment_profile.payment_method_type == 'bank' && !payment_profile.brand">Bank Account</span> 
                                        
                                        ending in 
                                            {{payment_profile.last_numbers}}</b>
                                        <div v-show="payment_profile.default == 1" style="color: green; font-size: 10px"><b>Default</b></div>
                                    </div>
                                    <div v-if="total_amount" class="col-md-1">
                                        <img v-if="payment_profile.brand && payment_profile.payment_method_type == 'card'" :src="'/images/creditCard/' + payment_profile.brand + '.png'"
                                            style="width: 50px; height: auto;">
                                        <img v-else-if="!payment_profile.brand && payment_profile.payment_method_type == 'card'" src="/images/creditCard/generic_card.png" style="width: 50px; height: auto;">
                                        <img v-else-if="!payment_profile.brand && payment_profile.payment_method_type == 'bank'" src="/images/bank-icon.png" style="width: 30px; height: auto;">
                                    </div>
                                    <div v-else  class="col-md-3" style="text-align: right;margin-top: 5px">
                                        <img v-if="payment_profile.brand && payment_profile.payment_method_type == 'card'" :src="'/images/creditCard/' + payment_profile.brand + '.png'"
                                            style="width: 50px; height: auto;">
                                        <img v-else-if="!payment_profile.brand && payment_profile.payment_method_type == 'card'" src="/images/creditCard/generic_card.png" style="width: 50px; height: auto;">
                                        <img v-else-if="!payment_profile.brand && payment_profile.payment_method_type == 'bank'" src="/images/bank-icon.png" style="width: 30px; height: auto;">
                                    </div>
                                    <div v-if="total_amount"  class="col-md-5" style="text-align: right;margin-top: 5px">
                                        <span v-show="payment_profile.default == 0" style="padding: 10px" title="Set as default method">
                                            <i @click="setAsDefault(payment_profile.payment_profile_id)" class="fa fa-chevron-up"
                                                style="cursor: pointer; font-size: 22px"></i>
                                        </span>
                                        <span style="padding: 10px" title="Remove">
                                            <i @click="removePaymentProfile(payment_profile.payment_profile_id)"
                                                class="fa fa-trash" style="cursor: pointer; font-size: 22px"></i>
                                        </span>
                                    </div>
                                    <div v-else  class="col-md-4" style="text-align: right;margin-top: 5px">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-show="showAddPaymentMethod" class="row">
                    <div :class="{'col-md-8' : order_id,'offset-md-2' : order_id, 'col-md-12' : typeof order_id == 'undefined'}">
                        <div>
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <label>
                                        <b>
                                            <h4>Select payment method</h4>
                                        </b>
                                    </label>
                                </div>
                            </div>
                            <ul id="payments_methods_options" style="list-style: none; margin-top: 10px">
                                <li v-if="!not_show_cash">
                                    <div class="skin skin-square">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span style="padding: 10px">
                                                    <input id="credit_card" type="radio" value="credit_card" name="payment_method"
                                                        checked>
                                                    <label for="credit_card">Credit/Debit Card</label>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <span style="padding: 10px">
                                                    <input id="bank_account" type="radio" value="bank_account" name="payment_method">
                                                    <label for="bank_account">Bank Account</label>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <span style="padding: 10px">
                                                    <input id="cash" type="radio" value="cash" name="payment_method">
                                                    <label for="cash">Money Order/Cash</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li v-else>
                                    <div class="skin skin-square">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span style="padding: 10px">
                                                    <input id="credit_card" type="radio" value="credit_card" name="payment_method"
                                                        checked>
                                                    <label for="credit_card">Credit/Debit Card</label>
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span style="padding: 10px">
                                                    <input id="bank_account" type="radio" value="bank_account" name="payment_method">
                                                    <label for="bank_account">Bank Account</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- <payout-credit-card :clientKey="client_key" :apiLoginID="api_id" :cardProcessor="card_processor" @addPaymentMethod="addPaymentMethodHandle"
                                                    v-show="payment_method == 'credit_card'"></payout-credit-card> -->
                <div v-show="showAddPaymentMethod" class="row" style="margin-top: 20px">
                    <div class="col-md-12">
                        <payout-credit-card :user_id="user.id" :cardProcessor="card_processor" :clientKey="client_key" @addPaymentMethod="addPaymentMethodHandle"
                            :apiLoginID="api_id" v-show="payment_method == 'credit_card'" :order_id="order_id" :total_amount="total_amount">
                        </payout-credit-card>
                        <add-bank-account :user="user" :card_processor="card_processor" :client_key="client_key" :total_amount="total_amount"
                            :api_id="api_id" v-show="payment_method == 'bank_account'" :order_id="order_id" @addPaymentMethod="addPaymentMethodHandle"></add-bank-account>
                        <payout-cash v-if="payment_method == 'cash'" :order_id="order_id" :total_amount="total_amount" @addPaymentMethod="addPaymentMethodHandle"></payout-cash>
                    </div>
                </div>

                <div v-if="!showAddPaymentMethod && total_amount" class="col-md-12 text-right" style="margin-top: 40px">
                    <button @click="payWithProfile()" class="btn btn-primary" :disabled="(total_amount <= 0 && customer_payment_profile != '')">
                        Pay ${{total_amount}}
                    </button>
                    <button @click="cancelPayment()" class="btn btn-default">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BankProcessor from '../../../../../../components/BankProcessorComponent.vue';
    // import CardProcessor from '../../../../../../components/CardProcessorComponent.vue';
    import CardProcessor from './CardProcessorComponent.vue';
    import CashProcessor from '../../../../../../components/CashProcessorComponent.vue';
    import PaymentServices from '../../../../../../services/PaymentServices';
    export default {
        props: ['user', 'card_processor', 'client_key', 'api_id','order_id','total_amount','not_show_cash'],
        components: {
            'add-bank-account': BankProcessor,
            'payout-credit-card': CardProcessor,
            'payout-cash': CashProcessor
        },
        mixins:[PaymentServices],
        data() {
            return {
                current_payment_profiles: [],
                current_customer_profile: {},
                payment_method: 'credit_card',
                showAddPaymentMethod: false,
                hide_payment_methods: false,
                customer_payment_profile:''
            }
        },
        methods: {
            hidePaymentMethods(val) {
                this.hide_payment_methods = val;
            },
            addPaymentMethodHandle(data) {
                // if(this.user.id > 0){
                //     location.reload();
                // }else{
                    if(this.order_id){
                         $.LoadingOverlay("show");
                        window.location.href = '/admin/store/edit-order/'+this.order_id
                    }else{
                        if(!data && this.current_payment_profiles && this.current_payment_profiles.length > 0){
                            this.showAddPaymentMethod = false;
                        }else{
                            this.$emit('sendPaymentId', data)
                            this.$emit('newPaymentAdded', data)
                        }
                    }
                // }
                // this.addPaymentMethod = false;
                // if (this.current_payment_profiles.length > 0) {
                //     for (var i in this.current_payment_profiles) {
                //         $('#payment_profile' + i).iCheck('uncheck');
                //     }
                // }
                // this.showAddCard = false;
                // this.paymentId = 0;
            },
            showAddNewPayment(){
                this.showAddPaymentMethod = true;
            },
            removePaymentProfile(payment_profile_id) {
                swal({
                    title: "Are you sure?",
                    text: "Even if you remove this payment method, any pending transaction will be completed.",
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
                            'user_id': this.user.id,
                            'payment_profile_id': payment_profile_id,
                        }
                        $.LoadingOverlay("show");
                        this.removePaymentProfileCall(data, this.removePaymentProfileCallback);
                    }
                });
            },
            removePaymentProfileCallback(response) {
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    for (var i in this.current_payment_profiles) {
                        if (this.current_payment_profiles[i].id == response.data.data) {
                            this.current_payment_profiles.splice(i, 1);
                        }
                    }
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
            payWithProfile(payment_profile_id) {
                if (this.customer_payment_profile != '') {
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
                            $.LoadingOverlay("show");
                            var data = {
                                'payment_profile_id': this.customer_payment_profile,
                                'user_id': this.user.id,
                                'order_id': this.order_id,
                            }
                            this.payWithProfileCall(data, this.payWithProfileCallback);
                        }
                    });
                }else{
                     swal({
                        title: "Please",
                        text: "Select a method payment",
                        icon: "warning"
                     })
                }
            },
            payWithProfileCallback(response) {
                var self = this;
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    var self = this;
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
            cancelPayment(){
                window.location.href="/admin/store/edit-order/"+this.order_id;
            },
            setAsDefault(payment_profile_id) {
                if (payment_profile_id) {
                    let data = {
                        'payment_profile_id': payment_profile_id,
                        'user_id': this.user.id
                    }
                    $.LoadingOverlay("show");
                    this.setAsDefaultCall(data, this.setAsDefaultCallback)
                }
            },
            setAsDefaultCallback(response) {
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.current_payment_profiles = response.data.data;
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
        },
        created() {
            if (this.user && this.user.customer_profile) {
                this.current_customer_profile= this.customer_profile
                if (this.user.customer_profile.payment_profiles.length > 0) {
                    this.current_payment_profiles = this.user.customer_profile.payment_profiles;
                }
            }
            if(this.current_payment_profiles.length == 0){
                this.showAddPaymentMethod = true;
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('#payments_list .skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
                $('#payments_methods_options .skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });

                $('#credit_card').on('ifChecked', function (event) {
                    self.payment_method = event.target.value;
                });

                $('#bank_account').on('ifChecked', function (event) {
                    self.payment_method = event.target.value;
                });

                $('#cash').on('ifChecked', function (event) {
                    self.payment_method = event.target.value;
                });

                if (self.current_payment_profiles.length > 0) {
                    for (var i in self.current_payment_profiles) {
                        $('#payment_profile' + i).on('ifChecked', function (event) {
                            self.customer_payment_profile = event.target.value;
                            self.$emit('sendPaymentId', $(this).attr('data-id'));
                        });

                        $('#payment_profile' + i).on('ifUnchecked', function (event) {
                            self.customer_payment_profile = '';
                        });
                    }
                }
            });
        }
    }
</script>

<style scoped>
    .card-body {
        min-height: 80vh;
    }
</style>