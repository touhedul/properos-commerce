<template>
    <div class="tt-form">
        <div class="tt-form__form">
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Name on card:</span>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.card_holder, 'input-bordered' : !error.card_holder}"
                            v-model="cardInfo.card_holder">
                        <div v-if="error.card_holder" class="input-error-msg">{{error_message.card_holder}}</div>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Card number:</span>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="cc_number" class="form-control colorize-theme6-bg" v-model="cardInfo.card_number" :class="{'input-bordered-error' : error.card_number, 'input-bordered' : !error.card_number }">
                        <div v-if="error.card_number" class="input-error-msg">{{error_message.card_number}}</div>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="cc_cvv" class="form-control colorize-theme6-bg" placeholder="CVV" v-model="cardInfo.cvv" :class="{'input-bordered-error' : error.cvv, 'input-bordered' : !error.cvv}">
                        <div v-if="error.cvv" class="input-error-msg">{{error_message.cvv}}</div>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Expiration date:</span>
                    </div>
                    <div class="col-md-4">
                        <select v-model="cardInfo.exp_month" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%">
                            <option value="" disabled selected>Month</option>
                            <option v-for="(item, key, index) in 12" :key="index">
                                <span v-if="item >= 1 && item <= 9">0{{item}}</span>
                                <span v-else>{{item}}</span>
                            </option>
                        </select>
                        <div v-if="error.exp_month" class="input-error-msg">{{error_message.exp_month}}</div>
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-4">
                        <select v-model="cardInfo.exp_year"  class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%" >
                            <option value="" disabled selected>Year</option>
                            <option style="padding: 10px" v-for="(item, key, index) in years" :key="index">{{item}}</option>
                        </select>
                        <div v-if="error.exp_year" class="input-error-msg">{{error_message.exp_year}}</div>
                    </div>
                </div>
            </label>
                <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Billing Address:</span>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_address, 'input-bordered' : !error.billing_address}"
                            v-model="cardInfo.billing_address">
                        <div v-if="error.billing_address" class="input-error-msg">{{error_message.billing_address}}</div>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Country:</span>
                    </div>
                    <div class="col-md-9">
                        <select id="select2-countries-card" v-model="cardInfo.billing_country" class="select2-placeholder form-control" style="height: 50px !important; width: 100%" :class="{'input-bordered-error' : error.billing_country, 'input-bordered' : !error.billing_country}"
                            data-placeholder="Select country...">
                            <option :value="-1"></option>
                            <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                        </select>
                        <div v-if="error.billing_country" class="input-error-msg">{{error_message.billing_country}}</div>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">City/Town:</span>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_city, 'input-bordered' : !error.billing_city}"
                            v-model="cardInfo.billing_city">
                        <div v-if="error.billing_city" class="input-error-msg">{{error_message.billing_city}}</div>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">State:</span>
                    </div>
                    <div class="col-md-9">
                        <div v-show="cardInfo.billing_country == 'US'">
                            <select v-model="cardInfo.billing_state" id="select2-states-card" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%"
                                data-placeholder="Select state...">
                                <option :value="-1"></option>
                                <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                            </select>
                        </div>
                        <input v-show="cardInfo.billing_country != 'US'" type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_state, 'input-bordered' : !error.billing_state}"
                            placeholder="Enter state" v-model="cardInfo.billing_state">
                        <div v-if="error.billing_state" class="input-error-msg">{{error_message.billing_state}}</div>

                        <!-- <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_state, 'input-bordered' : !error.billing_state}"
                            v-model="cardInfo.billing_state">
                        <div v-if="error.billing_state" class="input-error-msg">{{error_message.billing_state}}</div> -->
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-md-3">
                        <span class="ttg__required">Zip:</span>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_zip, 'input-bordered' : !error.billing_zip}"
                            v-model="cardInfo.billing_zip">
                        <div v-if="error.billing_zip" class="input-error-msg">{{error_message.billing_zip}}</div>
                    </div>
                </div>
            </label>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <span v-if="card_brand" style="float: left">
                        <img v-if="card_brand" :src="card_brand" style="float: right; width: 70px ;height: auto">
                    </span>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <a @click="cancelAddPaymentmethod()" class="btn" style="float: right; background-color: #c8c6c6; color: #575656">Cancel</a>
                    <a @click="addPaymentmethod()" class="btn" style="float: right; margin-right: 15px" :disabled="disable">Save</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CardProcessorServices from '../services/CardProcessorServices.js';
import misc from '../misc/payments.js';
import Helpers from '../misc/helpers.js';

export default {
    props:['clientKey','apiLoginID', 'cardProcessor', 'description','editPayment'],
    mixins: [CardProcessorServices, Helpers],
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
                billing_country: ''
            },
            error: {
                card_holder: false,
                card_number: false,
                exp_month: false,
                exp_year: false,
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
                cvv: '',
                billing_address: '',
                billing_city: '',
                billing_zip: '',
                billing_state: '',
                billing_country: ''
            },
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
            processing: false
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
            }
        },
    methods:{
        getPaymentMethodCallback(response){
            $.LoadingOverlay("hide");
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
        addPaymentmethod() {
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
                    customer_type: 'individual',
                    type: 'card',
                    profile: 'new',
                    save: true,
                    last_4: this.cardInfo.card_number.substring(this.cardInfo.card_number.length - 4)
                }
                if(this.editPayment > 0){
                    payment_method_data['delete'] = this.editPayment;
                }
                this.addPaymentMethodCall(payment_method_data, this.addPaymentMethodCallback);
                
            } else if(response.status == 0) {
                var errors = '';
                for (var i in response.errors){
                    errors += response.errors[i].text +'.';
                }
                swal('Error!',errors, 'error');
                $.LoadingOverlay("hide");
            }
            
        },
        addPaymentMethodCallback(code, message, data) {
            $.LoadingOverlay("hide");
            if (code == '200') {
                toastr.success(message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                this.$emit('addPaymentMethod', data.row);
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
            this.card_brand = '';
            $("#select2-countries-card").val('US').trigger('change');
            $("#select2-states-card").val(-1).trigger('change');
        },
        resetCardErrors() {
            this.error.card_holder = false;
            this.error.card_number = false;
            this.error.exp_month = false;
            this.error.exp_year = false;
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
                && this.cardInfo.billing_address != '' && this.cardInfo.billing_city != ''
                && this.cardInfo.billing_zip != '' && this.cardInfo.billing_state != ''  && this.cardInfo.billing_country != '') {
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
                if (this.cardInfo.exp_month == '') {
                    this.error.exp_month = true;
                    this.error_message.exp_month = 'Exp month is required';
                }
                if (this.cardInfo.cvv == '') {
                    this.error.cvv = true;
                    this.error_message.cvv = 'cvv is required';
                }
                if (this.cardInfo.exp_year == '') {
                    this.error.exp_year = true;
                    this.error_message.exp_year = 'Exp year is required';
                }
                if (this.cardInfo.billing_address == '') {
                    this.error.billing_address = true;
                    this.error_message.billing_address = 'Address is required';
                }
                if (this.cardInfo.billing_city == '') {
                    this.error.billing_city = true;
                    this.error_message.billing_city = 'City is required';
                }
                if (this.cardInfo.billing_zip == '') {
                    this.error.billing_zip = true;
                    this.error_message.billing_zip = 'Zip code is required';
                }
                if (this.cardInfo.billing_state == '') {
                    this.error.billing_state = true;
                    this.error_message.billing_state = 'State code is required';
                }
                if (this.cardInfo.billing_country == '') {
                    $("#select2-countries-card").select2({
                        containerCssClass: "select2-error"
                    });
                    this.error.billing_country = true;
                    this.error_message.billing_country = 'Country is required';
                }
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
            $("#select2-countries-card").select2();
            $("#select2-states-card").select2();
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
    }
}
</script>

