<template>
    <div>
        <form>
            <div class="row">
                <div class="col-12">
                    <a href="#" style="float: right" @click="showAddLocation()">Add store</a>
                    <h5 class="form-section"><i class="fa fa-location-arrow"></i> Store locations</h5>
                    <!-- <p style="margin-bottom:4px;"><strong>Store locations</strong></p> -->
                    <!-- <ul class="list-group">
                        <template v-if="store_locations.length > 0">
                            <li v-for="(location, key) in store_locations" class="list-group-item" :key="key" style="border:none; padding:0.5rem 0;">
                                <div class="row">
                                    <div class="col-md-9" style="align-self:center;">
                                        <a href="#" @click="editLocation(location)">{{location.label.toUpperCase()}}</a>
                                    </div>
                                    <div class="col-md-3 text-right" style="align-self:center;">
                                        <div style="padding: 5px; display:inline;">
                                            <a @click="removeLocation(location.id)" title="Remove location">
                                                <i class="fa fa-trash d-none d-sm-inline" style="color:red;font-size:18px;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr style="margin-top: 5px;margin-bottom: 5px;">
                            </li>
                        </template>
                        <template v-else>
                            <div class="text-center">No store locations
                            </div>
                        </template>
                    </ul> -->
                </div>
                <div class="col-12 text-center">
                    <div v-if="store_locations.length > 0" class="table-responsive">
                        <table id="popular-products" class="table table-hover mb-0" >
                            <thead>
                                <tr>
                                    <th style="border-top: none;text-align: left;">Store Name</th>
                                    <th style="text-align: right;border-top: none;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(location, index) in store_locations" :key="index">
                                    <td class="text-truncate" style="text-align: left;text-transform:capitalize;vertical-align:middle;">{{location.label}}</td>
                                    <td class="text-truncate" style="text-align: right;">
                                        <a @click="editLocation(location)" class="btn btn-primary"
                                            style="color: #fff; margin-top:2px; margin-bottom:2px;" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a id="removeLocation" @click="removeLocation(location.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <paginator style="margin-top:10px;" v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getStoreLocation()" :offset="offset"></paginator>
                    </div>
                    <div v-else class="table-responsive">
                        <div style="text-align: center">
                            <h6 style="color:#777;">No stores added</h6>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 text-center">
                    <paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getStoreLocation()" :offset="offset"></paginator>
                </div> -->
                <div class="modal fade text-left" id="create-locations-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom: 1px solid #98a4b845;">
                                <h4 v-if="location.id > 0" class="modal-title" id="myModalLabel1">Store location</h4>
                                <h4 v-else class="modal-title" id="myModalLabel1">New store location</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group mb-1 col-12">
                                        <label for="label">Label</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="location.label" v-bind:class="{'input-error-select' : error.label}" class="form-control"
                                                id="label">
                                            <span v-if="error.label" class="message-error">{{error_message.label}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-show="showAddress">
                                    <div class="form-group mb-1 col-12">
                                        <label for="address">Address</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="location.address" v-bind:class="{'input-error-select' : error.address}" class="form-control"
                                                id="address">
                                            <span v-if="error.address" class="message-error">{{error_message.address}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1 col-12 col-md-6">
                                        <label for="city">City</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="location.city" v-bind:class="{'input-error-select' : error.city}" class="form-control"
                                                id="city">
                                            <span v-if="error.city" class="message-error">{{error_message.city}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1 col-12 col-md-6">
                                        <label for="zip">Zip</label>
                                        <br>
                                        <div>
                                            <input type="text" v-model="location.zip" v-bind:class="{'input-error-select' : error.zip}" class="form-control"
                                                id="zip">
                                            <span v-if="error.zip" class="message-error">{{error_message.zip}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-1 col-12 col-md-6">
                                        <label for="country">Country</label>
                                        <br>
                                        <select id="select2-countries" v-model="location.country" class="select2-placeholder form-control input-bordered"
                                            style="height: 50px !important; width: 100%" data-placeholder="Select country...">
                                            <option :value="-1"></option>
                                            <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1 col-12 col-md-6">
                                        <label for="state">State</label>
                                        <br>
                                        <div v-show="location.country == 'US'">
                                            <select id="select2-states" v-model="location.state" class="select2-placeholder form-control input-bordered"
                                                style="height: 50px !important; width: 100%" data-placeholder="Select state...">
                                                <option :value="-1"></option>
                                                <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                            </select>
                                        </div>
                                        <input v-show="location.country != 'US'" type="text" v-model="location.state" v-bind:class="{'input-error-select' : error.state}"
                                            class="form-control" id="state">
                                        <span v-if="error.state" class="message-error">{{error_message.state}}</span>
                                    </div>
                                </div>
                                
                                
                                <!-- <div class="form-group mb-1 col-12">
                                    <label for="tax">Tax</label>
                                    <br>
                                    <div>
                                        <input type="text" v-model="location.tax" v-bind:class="{'input-error-select' : error.tax}" class="form-control"
                                            id="tax">
                                        <span v-if="error.tax" class="message-error">{{error_message.tax}}</span>
                                    </div>
                                </div> -->
                            </div>
                            <div class="modal-footer" style="border-top:none;">
                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-outline-primary" @click="saveLocation()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                location:{
                    id:0,
                    country: 'US',
                    state:'',
                    city:'',
                    zip:'',
                    address:'',
                    label:''
                },
                error:{
                    label: false,
                    state:false,
                    city:false,
                    zip:false,
                    address:false,
                },
                error_message:{
                    label: '',
                    state:'',
                    city:'',
                    zip:'',
                    address:'',
                },
                countries:[],
                states:[],
                store_locations:[],
                showAddress: true,
                taxes:[],
                paginator: {
                    total: 0,
                    per_page: 4,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    data_length: 0,
                    last_page: 0
                },
                offset:4,
                disabled: false
            }
        },
        methods: {
            showAddLocation(){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                this.location.id = 0;
                this.location.country = 'US';
                this.location.state = '';
                this.location.city = '';
                this.location.address = '';
                this.location.zip = '';
                this.location.label = '';
                // this.world = false;
                var self = this;
                // this.disabled = false;
                setTimeout(function(){
                    $.LoadingOverlay("show");
                    self.getCountriesCall(self.getCountriesCallback);
                    $("#create-locations-modal").modal('show');
                },50)
            },
            editLocation(val){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                $.LoadingOverlay("show");
                this.getCountriesCall(this.getCountriesCallback);
                var self = this;
                setTimeout(function(){
                    self.location.id = val.id;
                    self.location.country = val.country.toUpperCase();
                    self.location.state = val.state.toUpperCase();
                    self.location.city = val.city;
                    self.location.zip = val.zip;
                    self.location.label = val.label.toUpperCase();
                    self.location.address = val.address;
                    
                    $("#create-locations-modal").modal('show');
                },100)
            },
            saveLocation(){
                for(var i in this.error){
                    this.error[i] = false;
                    this.error_message[i] = '';
                }
                if(this.location.state != '' && this.location.city != '' && this.location.address != '' && this.location.zip != '' && this.location.label != ''){
                    $.LoadingOverlay("show");
                    if(this.location.id > 0){
                        this.updateLocationCall(this.location, this.saveLocationCallback);
                    }else{
                        this.createLocationCall(this.location, this.saveLocationCallback);
                    }
                }else{
                    if(this.location.state == ''){
                        this.error.state = true;
                        this.error_message.state = 'State is required';
                    }
                    if(this.location.city == ''){
                        this.error.city = true;
                        this.error_message.city = 'City is required';
                    }
                    if(this.location.address == ''){
                        this.error.address = true;
                        this.error_message.address = 'Address is required';
                    }
                    if(this.location.zip == ''){
                        this.error.zip = true;
                        this.error_message.zip = 'Zip is required';
                    }
                    if(this.location.label == ''){
                        this.error.label = true;
                        this.error_message.label = 'Label is required';
                    }
                }
            },
            saveLocationCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getStoreLocation();
                    $("#create-locations-modal").modal('hide');
                }else{
                    if (Helpers.isAssoc(response.errors)) {
                        let errors = [];
                        for (var i in response.errors) {
                            var string
                            errors.push('<span>' + response.errors[i] + '</span></br>')
                            this.error[i] = true;
                            this.error_message[i] = response.errors[i][0];
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            getStoreLocation() {
                $.LoadingOverlay("show");
                var params = {
                    page: this.paginator.current_page,
                    limit: this.offset,
                    fields: '*'
                };
                this.getStoreLocationCall(params, this.getStoreLocationCallback);
            },
            getStoreLocationCallback(response) {
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
                    this.store_locations = response.data;
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
                    if (this.location.country == 'US') {
                        this.getStates();
                        $('#select2-states').val(this.location.state).trigger('change');
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
            removeLocation(id) {
                swal({
                    title: "Are you sure?",
                    text: "Please confirm you want to remove this store location.",
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
                        this.removeLocationCall(id, this.removeLocationCallback);
                    }
                });
            },
            removeLocationCallback(response) {
                if (response.status == 1) {
                    toastr.success(response.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    this.getStoreLocation();
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
        created() {
            this.getStoreLocation();
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

                 $("#select2-countries").select2({
                     dropdownParent: $('#create-locations-modal'),
                    matcher: self.matchCustom
                 });
                $("#select2-states").select2({
                     dropdownParent: $('#create-locations-modal'),
                     matcher: self.matchCustom
                 });

                $('#select2-countries').on('change', function (e) {
                    self.location.country = $('#select2-countries').select2('data')[0].id;
                    self.location.state = "";
                    if (self.location.country == 'US') {
                        self.getStates();
                    }
                    if (self.location.country != '') {
                        $(".select2-selection").removeClass("select2-error");
                    }

                });

                $('#select2-states').on('change', function (e) {
                    self.location.state = $('#select2-states').select2('data')[0].id;
                    if (self.location.state != '') {
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