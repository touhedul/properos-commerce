<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Taxes</h4>
                            </div>
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                                 <form>
                                    <a href="#" style="float: right" @click="showAddRate()">Add Rate</a>
                                    <h5 class="form-section"><i class="fa fa-barcode"></i>Rates</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div v-if="taxes.length > 0" class="table-responsive">
                                                <table class="table table-hover mb-0" >
                                                    <thead>
                                                        <tr>
                                                            <th style="border-top: none;">Destination</th>
                                                            <th style="text-align: center;border-top: none;">Tax Rate</th>
                                                            <th style="text-align: center;border-top: none;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(tax, index) in taxes" :key="index">
                                                            <td class="text-truncate" style="vertical-align:middle;">
                                                                {{tax.label}}
                                                            </td>
                                                            <td class="text-truncate" style="text-align: center;vertical-align:middle;">
                                                                {{tax.tax}}%
                                                            </td>
                                                            <td class="text-truncate" style="text-align: right;">
                                                                <a  @click="editTax(tax.id, tax.country, tax.state, tax.city, tax.tax)" class="btn btn-primary"
                                                                    style="color: #fff; margin-top:2px; margin-bottom:2px;" title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <a id="removeTax" @click="removeTax(tax.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                 <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getTaxes()" :offset="offset"></paginator>
                                            </div>
                                            <div v-else class="table-responsive">
                                                <div style="text-align: center">
                                                    <h6 style="color:#777;">No rates defined</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 </form>
                            </div>
                        </div>
                        <div class="modal fade text-left" id="create-rate-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                            <div class="modal-dialog modal-xs" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="border-bottom: 1px solid #98a4b845;">
                                        <h4 v-if="tax_rate.id > 0" class="modal-title" id="myModalLabel1">Tax Rate</h4>
                                        <h4 v-else class="modal-title" id="myModalLabel1">New Tax Rate</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group mb-1 col-12">
                                            <fieldset>
                                                <div class="input-group">
                                                    <div class="skin skin-square">
                                                        <div class="form-group text-right">
                                                            <input type="checkbox" id="rest_of_world" :disabled="disabled">
                                                            <label for="rest_of_world">Rest of World</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div v-show="showAddress">
                                            <div class="form-group mb-1 col-12">
                                                <label for="country">Country</label>
                                                <br>
                                                <select id="select2-countries" v-model="tax_rate.country" class="select2-placeholder form-control input-bordered"
                                                    style="height: 50px !important; width: 100%" data-placeholder="Select country...">
                                                    <option :value="-1"></option>
                                                    <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-1 col-12">
                                                <label for="state">State</label>
                                                <br>
                                                <div v-show="tax_rate.country == 'US'">
                                                    <select id="select2-states" v-model="tax_rate.state" class="select2-placeholder form-control input-bordered"
                                                        style="height: 50px !important; width: 100%" data-placeholder="Select state...">
                                                        <option :value="-1"></option>
                                                        <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                                    </select>
                                                </div>
                                                <input v-show="tax_rate.country != 'US'" type="text" v-model="tax_rate.state" v-bind:class="{'input-error-select' : error.state}"
                                                    class="form-control" id="state">
                                                <span v-if="error.state" class="message-error">{{error_message.state}}</span>
                                            </div>
                                            <div class="form-group mb-1 col-12">
                                                <label for="city">City</label>
                                                <br>
                                                <div>
                                                    <input type="text" v-model="tax_rate.city" v-bind:class="{'input-error-select' : error.city}" class="form-control"
                                                        id="city">
                                                    <span v-if="error.city" class="message-error">{{error_message.city}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1 col-12">
                                            <label for="tax">Tax</label>
                                            <br>
                                            <div>
                                                <input type="text" v-model="tax_rate.tax" v-bind:class="{'input-error-select' : error.tax}" class="form-control"
                                                    id="tax">
                                                <span v-if="error.tax" class="message-error">{{error_message.tax}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top:none;">
                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-outline-primary" @click="saveTax()">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SettingServices from '../../services/SettingServices.js';
    import OrdersServices from '../../services/OrdersServices';
    import Paginator from '../../../../../../components/Paginator.vue'
    export default {
        mixins:[SettingServices, OrdersServices],
        components: {
            'paginator': Paginator
        },
        data() {
            return {
                tax_rate:{
                    id:0,
                    country:'US',
                    state:'',
                    city:'',
                    tax:0,
                    world:false,
                    label:''
                },
                error:{
                    state: false,
                    city: false,
                    tax:false
                },
                error_message:{
                    state: '',
                    city: '',
                    tax:''
                },
                countries:[],
                states:[],
                showAddress: true,
                taxes:[],
                paginator: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    data_length: 0,
                    last_page: 0
                },
                offset: 10,
                disabled: false
            }
        },
        methods: {
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
            showAddRate(){
                this.tax_rate.id = 0;
                this.tax_rate.country = 'US';
                this.tax_rate.state = '';
                this.tax_rate.city = '';
                this.tax_rate.tax = '';
                this.world = false;
                var self = this;
                $('#rest_of_world').iCheck('uncheck');
                this.disabled = false;
                setTimeout(function(){
                    $("#select2-countries").select2({
                        dropdownParent: $('#create-rate-modal'),
                         matcher: self.matchCustom
                    });
                    $("#select2-states").select2({
                        dropdownParent: $('#create-rate-modal'),
                         matcher: self.matchCustom
                    });
                    // $.LoadingOverlay("show");
                    self.getCountriesCall(self.getCountriesCallback);
                    $("#create-rate-modal").modal('show');
                },50)
            },
            editTax(id,country, state, city, tax, label){
                var self = this;
               $("#select2-countries").select2({
                    dropdownParent: $('#create-rate-modal'),
                     matcher: self.matchCustom
                });
                $("#select2-states").select2({
                    dropdownParent: $('#create-rate-modal'),
                     matcher: self.matchCustom
                });
                $.LoadingOverlay("show");
                this.getCountriesCall(this.getCountriesCallback);
                setTimeout(function(){
                    self.tax_rate.id = id;
                    self.tax_rate.country = country;
                    self.tax_rate.state = state;
                    self.tax_rate.city = city;
                    self.tax_rate.tax = tax;
                    self.disabled = true;
                    
                    $("#create-rate-modal").modal('show');
                    if(country == null && state == null && city == null){
                        $('#rest_of_world').iCheck('check');
                        self.tax_rate.world = true;
                    }
                },100)
            },
            saveTax(){
                // if(this.showAddress == true){
                    if(this.tax_rate.tax != ''){
                        $.LoadingOverlay("show");
                    if(this.tax_rate.id > 0){
                        this.updateTaxCall(this.tax_rate, this.saveTaxCallback);
                    }else{
                        this.createTaxCall(this.tax_rate, this.saveTaxCallback);
                    }
                    }else{
                        // if(this.tax_rate.state == ''){
                        //     this.error.state = true;
                        //     this.error_message.state = 'State is required';
                        // }
                        // if(this.tax_rate.city == ''){
                        //     this.error.city = true;
                        //     this.error_message.city = 'City is required';
                        // }
                        if(this.tax_rate.tax == ''){
                            this.error.tax = true;
                            this.error_message.tax = 'Tax is required';
                        }
                    }
                // }else{
                //     if(this.tax_rate.tax != ''){
                //         $.LoadingOverlay("show");
                //         if(this.tax_rate.id > 0){
                //             this.updateTaxCall(this.tax_rate, this.saveTaxCallback);
                //         }else{
                //             this.createTaxCall(this.tax_rate, this.saveTaxCallback);
                //         }
                //     }else{
                //         this.error.tax = true;
                //         this.error_message.tax = 'Tax is required';

                //     }
                // }
            },
            saveTaxCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getTaxes();
                    $("#create-rate-modal").modal('hide');
                }else{
                    if (Helpers.isAssoc(response.errors)) {
                        let errors = [];
                        for (var i in response.errors) {
                            var string
                            errors.push('<span>' + response.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            getTaxes() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: this.offset,
                    fields: '*'
                };
                this.getTaxesCall(params, this.getTaxesCallback);
            },
            getTaxesCallback(response) {
                $.LoadingOverlay("hide");
                if (response.status == 1) {
                    if (response.data.length > 0) {
                    this.paginator = response.pagination;
                    } else {
                    this.paginator = {
                        total: 0,
                        per_page: 2,
                        from: 1,
                        to: 0,
                        current_page: 1
                    };
                    }
                    this.taxes = response.data;
                } else {
                    toastr.error(response.errors[0], "An error has occurred", {
                    positionClass: "toast-top-center",
                    containerId: "toast-top-center"
                    });
                }
            },
            getCountriesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.countries = data;
                    if (this.tax_rate.country == 'US') {
                        this.getStates();
                        $('#select2-states').val(this.tax_rate.state).trigger('change');
                    }
                }
            },
            getStates() {
                $.LoadingOverlay("show");
                this.getStatesCall(this.getStatesCallback);
            },
            getStatesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.states = data;
                }
            },
            removeTax(id) {
                swal({
                    title: "Are you sure?",
                    text: "Please confirm you want to remove this tax rate.",
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
                        this.removeTaxCall(id, this.removeTaxCallback);
                    }
                });
            },
            removeTaxCallback(response) {
                if (response.status == 1) {
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getTaxes();
                } else {
                    if (Helpers.isAssoc(response.errors)) {
                        let errors = [];
                        for (var i in response.errors) {
                            var string
                            errors.push('<span>' + response.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
                $.LoadingOverlay("hide");
            },
        },
        created() {
            this.getTaxes();
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
                $('#rest_of_world').on('ifChecked', function (event) {
                    self.showAddress = false;
                    self.world = true;
                });

                $('#rest_of_world').on('ifUnchecked', function (event) {
                    self.showAddress = true;
                    self.world = false;
                });

                $('#select2-countries').on('change', function (e) {
                    self.tax_rate.country = $('#select2-countries').select2('data')[0].id;
                    self.tax_rate.state = "";
                    if (self.tax_rate.country == 'US') {
                        self.getStates();
                    }
                    if (self.tax_rate.country != '') {
                        $(".select2-selection").removeClass("select2-error");
                    }

                });

                $('#select2-states').on('change', function (e) {
                    self.tax_rate.state = $('#select2-states').select2('data')[0].id;
                    if (self.tax_rate.state != '') {
                        $(".select2-selection").removeClass("select2-error");
                    }
                });
            });
        }
    }
</script>
<style>

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }
</style>