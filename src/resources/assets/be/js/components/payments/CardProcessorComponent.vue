<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                <!--  <div v-if="bus.current_payment_profiles.length > 0" class="row">
                    <div class="col-md-12">
                        <div style="padding-top: 10px; padding-bottom: 10px; font-size: 12px">Pay with a new card</div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <label>
                            <b>Card Holder</b>
                        </label>
                        <div class="input-group">
                            <input v-model="cardInfo.card_holder" type="text" class="form-control" :class="{'input-error-select' : error.card_holder}">
                        </div>
                        <span v-if="error.card_holder" class="message-error">{{error_message.card_holder}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-6">
                        <label>
                            <b>Card number</b>
                        </label>
                        <div class="input-group">
                            <input id="cc_number" v-model="cardInfo.card_number" type="text" class="form-control"
                                :class="{'input-error-select' : error.card_number}">
                        </div>
                        <span v-if="error.card_number" class="message-error">{{error_message.card_number}}</span>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <b>Exp. date
                                <span style="font-size: 12px">(MM/YYYY)</span>
                            </b>
                        </label>
                        <div class="input-group">
                            <input v-model="exp_date" type="text" class="form-control" :class="{'input-error-select' : error.exp_date}">
                        </div>
                        <span v-if="error.exp_date" class="message-error">{{error_message.exp_date}}</span>
                    </div>
                    <div class="col-md-2">
                        <label>
                            <b>Cvv</b>
                        </label>
                        <div class="input-group">
                            <input v-model="cardInfo.cvv" type="text" class="form-control" :class="{'input-error-select' : error.cvv}">
                        </div>
                        <span v-if="error.cvv" class="message-error">{{error_message.cvv}}</span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-6">
                        <label>
                            <b>Zip code</b>
                        </label>
                        <div class="input-group">
                            <input v-model="cardInfo.billing_zip" type="text" class="form-control" :class="{'input-error-select' : error.billing_zip}">
                        </div>
                        <span v-if="error.billing_zip" class="message-error">{{error_message.billing_zip}}</span>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <b>Account type</b>
                        </label>
                        <fieldset class="form-group position-relative">
                            <select v-model="cardInfo.customer_type" class="form-control" id="role" :class="{'input-error-select' : error.account_type}">
                                <option v-if="account_types.length > 0" v-for="(account_type, index) in account_types"
                                    :key="index" :value="account_type.name">{{account_type.label}}</option>
                            </select>
                            <span v-if="error.account_type" class="message-error">{{error_message.account_type}}</span>
                        </fieldset>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-6" style="padding-left: 5px">
                        <div class="input-group" v-if="user_id > 0">
                            <div class="skin skin-square">
                                <span style="padding: 10px">
                                    <input id="save_method" type="checkbox" name="save_method">
                                    <label for="save_method">
                                        Save for future use
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right" style="padding-left: 0px">
                        <span style="padding: 5px">
                            <img src="/images/creditCard/visa.png" style="width: 40px; height: auto;">
                        </span>
                        <span style="padding: 5px">
                            <img src="/images/creditCard/mastercard.png" style="width: 40px; height: auto;">
                        </span>
                        <span style="padding: 5px">
                            <img src="/images/creditCard/amex.png" style="width: 40px; height: auto;">
                        </span>
                        <span style="padding: 5px">
                            <img src="/images/creditCard/discover.png" style="width: 40px; height: auto;">
                        </span>
                        <span style="padding: 5px">
                            <img src="/images/creditCard/jcb.png" style="width: 40px; height: auto;">
                        </span>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <!-- <div class="col-md-6" style="padding-left: 5px">
                        <div class="input-group">
                            <div class="skin skin-square">
                                <span style="padding: 10px">
                                    <input id="recurring_payments" type="checkbox" name="recurring_payments">
                                    <label for="recurring_payments">
                                        Enroll in automatic payments
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-12 text-right" style="margin-top: 20px">
                        <button @click="payNow()" class="btn btn-primary" :disabled="(total_amount <= 0)">
                            Pay ${{total_amount}}
                        </button>
                        <!-- <button v-else @click="updateSubscriptionPayment()" class="btn btn-primary">
                            Save
                        </button> -->
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
    import Helpers from '../../../../../../misc/helpers';
    import PaymentServices from '../../../../../../services/PaymentServices';
    import misc from '../../../../../../misc/payments.js';
    import CardProcessorServices from '../../../../../../services/CardProcessorServices.js';
    export default {
        props:['clientKey','apiLoginID', 'cardProcessor', 'description','editPayment','user_id','order_id','total_amount'],
        mixins: [CardProcessorServices, Helpers, PaymentServices],
        data(){
            return{
                cardInfo: {
                    card_holder: '',
                    card_number: '',
                    cvv: '',
                    exp_month: '',
                    exp_year: '',
                    billing_address: '',
                    billing_city: '',
                    billing_zip: '',
                    billing_state: '',
                    billing_country: '',
                    customer_type:'',
                    save_method:false
                },
                error: {
                    card_holder: false,
                    card_number: false,
                    exp_month: false,
                    exp_year: false,
                    exp_date: false,
                    cvv: false,
                    billing_address: false,
                    billing_city: false,
                    billing_zip: false,
                    billing_state: false,
                    billing_country: false
                },
                error_message: {
                    card_holder: '',
                    card_number: '',
                    exp_month: '',
                    exp_year: '',
                    exp_date: '',
                    cvv: '',
                    billing_address: '',
                    billing_city: '',
                    billing_zip: '',
                    billing_state: '',
                    billing_country: ''
                },
                exp_date:'',
                // valid_card: false,
                card_brand: '',
                years:[],
                countries:[],
                states:[],
                oldCardInfo:{
                    card_holder: '',
                    card_number: '',
                    cvv: '',
                    exp_month: '',
                    exp_year: '',
                    billing_address: '',
                    billing_city: '',
                    billing_zip: '',
                    billing_state: 'US',
                    billing_country: ''
                },
                disable: true,
                processing: false,
                account_types: [
                    {
                        name: 'individual',
                        label: 'Personal'
                    },
                    {
                        name: 'business',
                        label: 'Business'
                    }
                ],
            }
        },
        watch: {
                'cardInfo.card_holder'(val) {
                    if (val != '') {
                        this.error.card_holder = false;
                        this.error_message.card_holder = '';
                    }
                    if(this.cardInfo.card_holder != this.oldCardInfo.card_holder){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.card_number'(val) {
                    if (val != '') {
                        this.error.card_number = false;
                        this.error_message.card_number = '';
                    }
                    if(this.cardInfo.card_number != this.oldCardInfo.card_number){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.exp_month'(val) {
                    if (val != '') {
                        this.error.exp_month = false;
                        this.error_message.exp_month = '';
                    }
                    if(this.cardInfo.exp_month != this.oldCardInfo.exp_month){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.exp_year'(val) {
                    if (val != '') {
                        this.error.exp_year = false;
                        this.error_message.exp_year = '';
                    }
                    if(this.cardInfo.exp_year != this.oldCardInfo.exp_year){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.cvv'(val) {
                    if (this.val != '') {
                        this.error.cvv = false;
                        this.error_message.cvv = '';
                    }
                    if (Helpers.validateCVV(val)) {
                        this.error.cvv = false;
                    }
                    if(this.cardInfo.cvv != this.oldCardInfo.cvv){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.billing_address'(val) {
                    if (this.val != '') {
                        this.error.billing_address = false;
                        this.error_message.billing_address = '';
                    }
                    if(this.cardInfo.billing_address != this.oldCardInfo.billing_address){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.billing_city'(val) {
                    if (this.val != '') {
                        this.error.billing_city = false;
                        this.error_message.billing_city = '';
                    }
                    if(this.cardInfo.billing_city != this.oldCardInfo.billing_city){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.billing_zip'(val) {
                    if (this.val != '') {
                        this.error.billing_zip = false;
                        this.error_message.billing_zip = '';
                    }
                    if(this.cardInfo.billing_zip != this.oldCardInfo.billing_zip){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.billing_state'(val) {
                    if (this.val != '') {
                        this.error.billing_state = false;
                        this.error_message.billing_state = '';
                    }
                    if(this.cardInfo.billing_state != this.oldCardInfo.billing_state){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'cardInfo.billing_country'(val) {
                    if (val != '') {
                        this.error.billing_country = false;
                        this.error_message.billing_country = '';
                    }
                    if(this.cardInfo.billing_country != this.oldCardInfo.billing_country &&  val != 'US'){
                        this.disable = false;
                        if(this.cardInfo.cvv == 'XXX'){
                            this.cardInfo.cvv = '';
                        }
                        if(this.cardInfo.card_number.indexOf("XX") !== -1){
                            this.cardInfo.card_number = '';
                        }
                    }
                },
                'editPayment'(val){
                    if(val > 0){
                        $.LoadingOverlay("show");
                        this.getPaymentMethod(val, this.getPaymentMethodCallback);
                    }else{

                    }
                },
                'exp_date'(val) {
                    if (val != '') {
                        this.error.exp_date = false;
                        this.error_message.exp_date = '';
                    }
                    if (Helpers.validateExpDateMMYYYY(val)) {
                        this.error.exp_date = false;
                    } else {
                        this.error.exp_date = true;
                        this.error_message.exp_date = 'Exp. date is incorrect.'
                    }
                },
            },
        methods:{
            getPaymentMethodCallback(response){
                if(response.status > 0){
                    this.cardInfo.card_holder = response.data.billing_firstname +' '+ response.data.billing_lastname;
                    this.cardInfo.card_number = response.data.card_number;
                    this.cardInfo.billing_address = response.data.billing_address;
                    this.cardInfo.billing_city = response.data.billing_city;
                    this.cardInfo.billing_country = response.data.billing_country;
                    this.cardInfo.billing_state = response.data.billing_state;
                    this.cardInfo.billing_zip = response.data.billing_zip;
                    var date = response.data.expiration_date.split('/');
                    this.cardInfo.exp_month = date[0];
                    this.cardInfo.exp_year = date[1];
                    this.cardInfo.cvv = 'XXX';
                    this.oldCardInfo = Object.assign({}, this.oldCardInfo, this.cardInfo);
                    $("#select2-countries-card").val(this.cardInfo.billing_country).trigger('change');
                    $("#select2-states-card").val(this.cardInfo.billing_state).trigger('change');
                }else{
                    swal('Error!',response.errors[0], 'error');
                }
                $.LoadingOverlay("hide");
            },
            payNow() {
                var date = this.exp_date.split('/');
                this.cardInfo.exp_month = date[0];
                if(date[1]){
                    this.cardInfo.exp_year = date[1];
                }
                if(this.oldCardInfo.card_holder != this.cardInfo.card_holder || this.oldCardInfo.card_number != this.cardInfo.card_number
                || this.oldCardInfo.billing_address != this.cardInfo.billing_address || this.oldCardInfo.billing_city != this.cardInfo.billing_city || this.oldCardInfo.billing_state != this.cardInfo.billing_state
                || this.oldCardInfo.billing_zip != this.cardInfo.billing_zip || this.oldCardInfo.exp_month != this.cardInfo.exp_month || this.oldCardInfo.exp_year != this.cardInfo.exp_year){
                    if (this.validateCardInfo()) {
                        $.LoadingOverlay("show");
                        var paymentData = {
                            clientKey: this.clientKey,
                            apiLoginID: this.apiLoginID,
                            cardInfo: this.cardInfo,
                            description: this.description,
                            type: 'card'
                        };
                        var payment = payments.driver(this.cardProcessor);
                        payment.card.createCardToken(paymentData, this.createCardTokenCallback);
                    }
                }else{
                    this.cancelAddPaymentmethod();
                }
            },
            createCardTokenCallback(response){
                if (response.status >0) {
                    var payment_method_data = {
                        card_info: {
                            card_holder: this.cardInfo.card_holder,
                            billing_address: this.cardInfo.billing_address,
                            billing_city: this.cardInfo.billing_city,
                            billing_state: this.cardInfo.billing_state,
                            billing_zip: this.cardInfo.billing_zip,
                            billing_country: this.cardInfo.billing_country,
                            exp_month: this.cardInfo.exp_month,
                            exp_year: this.cardInfo.exp_year,
                            token: response.data.token,
                            description: response.data.description,
                        },
                        customer_type: this.cardInfo.customer_type,
                        type: 'card',
                        profile: 'new',
                        save: this.cardInfo.save_method,
                        user_id: this.user_id,
                        order_id: this.order_id,
                        last_4: this.cardInfo.card_number.substring(this.cardInfo.card_number.length - 4)
                    }
                    if(this.editPayment > 0){
                        payment_method_data['delete'] = this.editPayment;
                    }
                    this.addPaymentCall(payment_method_data, this.addPaymentCallback);
                    
                } else if(response.status == 0) {
                    var errors = '';
                    for (var i in response.errors){
                        errors += response.errors[i].text +'.';
                    }
                    swal('Error!',errors, 'error');
                    $.LoadingOverlay("hide");
                }
                
            },
            addPaymentCallback(code, message, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    toastr.success(message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                    this.cancelAddPaymentmethod();
                }
                else if (code == '001') {
                    toastr.error(data, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                }
            },
            cancelAddPaymentmethod() {
                this.$emit('addPaymentMethod', false);
                this.resetCardInfo();
                this.resetCardErrors();
            },
            resetCardInfo() {
                this.cardInfo.card_holder = '';
                this.cardInfo.card_number = '';
                this.cardInfo.cvv = '';
                this.cardInfo.card_brand = '';
                this.cardInfo.billing_address = '';
                this.cardInfo.billing_city = '';
                this.cardInfo.billing_zip = '';
                this.cardInfo.billing_country = '';
                this.cardInfo.billing_state = '';
                this.cardInfo.exp_month = '';
                this.cardInfo.exp_year = '';
                this.exp_date = '';
                this.card_brand = '';
                $("#select2-countries-card").val('US').trigger('change');
                $("#select2-states-card").val(-1).trigger('change');
            },
            resetCardErrors() {
                this.error.card_holder = false;
                this.error.card_number = false;
                this.error.exp_month = false;
                this.error.exp_year = false;
                this.error.exp_date = false;
                this.error.cvv = false;
                this.error.billing_address = false;
                this.error.billing_city = false;
                this.error.billing_zip = false;
                this.error.billing_state = false;
                this.error.billing_country = false;
            },
            validateCardInfo() {
                if (this.cardInfo.card_holder != '' && this.cardInfo.card_number != ''
                    && this.cardInfo.exp_month != '' && this.cardInfo.exp_month != '' && this.cardInfo.cvv != ''
                    && this.cardInfo.billing_zip != '') {
                    return true;
                }
                else {
                    if (this.cardInfo.card_holder == '') {
                        this.error.card_holder = true;
                        this.error_message.card_holder = 'Card holder name is required';
                    }
                    if (this.cardInfo.card_number == '') {
                        this.error.card_number = true;
                        this.error_message.card_number = 'Card number is required';
                    }
                    // if (this.cardInfo.exp_month == '') {
                    //     this.error.exp_month = true;
                    //     this.error_message.exp_month = 'Exp month is required';
                    // }
                    if (this.cardInfo.cvv == '') {
                        this.error.cvv = true;
                        this.error_message.cvv = 'cvv is required';
                    }
                    if (this.cardInfo.exp_year == '' || this.cardInfo.exp_month == '') {
                        this.error.exp_date = true;
                        this.error_message.exp_date = 'Exp year is required';
                    }
                    // if (this.cardInfo.billing_address == '') {
                    //     this.error.billing_address = true;
                    //     this.error_message.billing_address = 'Address is required';
                    // }
                    // if (this.cardInfo.billing_city == '') {
                    //     this.error.billing_city = true;
                    //     this.error_message.billing_city = 'City is required';
                    // }
                    if (this.cardInfo.billing_zip == '') {
                        this.error.billing_zip = true;
                        this.error_message.billing_zip = 'Zip code is required';
                    }
                    // if (this.cardInfo.billing_state == '') {
                    //     this.error.billing_state = true;
                    //     this.error_message.billing_state = 'State code is required';
                    // }
                    // if (this.cardInfo.billing_country == '') {
                    //     $("#select2-countries-card").select2({
                    //         containerCssClass: "select2-error",
                    //         matcher: this.matchCustom
                    //     });
                    //     this.error.billing_country = true;
                    //     this.error_message.billing_country = 'Country is required';
                    // }
                }
            },
            getCountriesCallback(code, msg, data) {
                if (code == '200') {
                    this.countries = data;
                    var _this = this;
                    this.$nextTick(function(){
                        let data = {
                            name: 'United States',
                            id: 'US'
                        };
                        var option = new Option(data.name, data.id, true, true);
                        $("#select2-countries-card").append(option).trigger('change');
                        $("#select2-countries-card").trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                        
                    })
                    
                }
            },
            getStates() {
                this.processing = true;
                $.LoadingOverlay("show");
                this.getStatesCall(this.getStatesCallback);
            },
            getStatesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.states = data;
                    this.processing = false;
                }
            },
            matchCustom(params, data) {
                if ($.trim(params.term) === '') {
                return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                return null;
                }

                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                if (data.text.toUpperCase().startsWith(params.term.toUpperCase())) {
                var modifiedData = $.extend({}, data, true);

                return modifiedData;
                }
                // Return `null` if the term should not be displayed
                return null;
            },
        },
        created(){
            var date = new Date,
                years = [],
                year = date.getFullYear();

            for (var i = year; i < year + 20; i++) {
                this.years.push(i);
            }
            this.getCountriesCall(this.getCountriesCallback);
            
        },
        mounted(){
            if(this.cardProcessor.toLowerCase() == 'stripe'){
                Stripe.setPublishableKey(this.clientKey);
            }

            var self = this;
            this.$nextTick(function () {
                $("#select2-countries-card").select2({
                    matcher: self.matchCustom
                });
                $("#select2-states-card").select2({
                    matcher: self.matchCustom
                });
            });

            $('#select2-countries-card').on('change', function (e) {
                self.cardInfo.billing_country = $('#select2-countries-card').select2('data')[0].id;
                if (self.cardInfo.billing_country == 'US' && self.processing == false) {
                    self.getStates();
                }
                if (self.cardInfo.billing_country != '') {
                    $(".select2-selection").removeClass("select2-error");
                }
            });

            $('#select2-states-card').on('change', function (e) {
                self.cardInfo.billing_state = $('#select2-states-card').select2('data')[0].id;
                if (self.cardInfo.billing_state != '') {
                    $(".select2-selection").removeClass("select2-error");
                }
            });

            $('#save_method').on('ifChecked', function (event) {
                self.cardInfo.save_method = true;
            });

            $('#save_method').on('ifUnchecked', function (event) {
                self.cardInfo.save_method = false;
            });
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