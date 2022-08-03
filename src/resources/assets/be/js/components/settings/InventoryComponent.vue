<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Inventory</h4>
                            </div>
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a data-action="collapse">
                                            <i class="ft-minus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="reload">
                                            <i class="ft-rotate-cw"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="expand">
                                            <i class="ft-maximize"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="close">
                                            <i class="ft-x"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                                <form>
                                    <h5 class="form-section"><i class="fa fa-barcode"></i> SKU Number</h5>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <fieldset class="form-group">
                                                <fieldset>
                                                    <label class="custom-control custom-radio">
                                                        <input id="sku_random" v-model="sku_number_type" value="random" name="media" :checked="sku_number_type=='random'"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">Random</span>
                                                    </label>
                                                </fieldset>
                                                <fieldset>
                                                    <label class="custom-control custom-radio">
                                                        <input id="sku_consecutive" v-model="sku_number_type" value="consecutive" name="media" type="radio" class="custom-control-input"
                                                        :checked="sku_number_type=='consecutive'">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">Consecutive</span>
                                                    </label>
                                                </fieldset>

                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <template v-if="sku_number_type=='consecutive'">
                                                <div>
                                                    <label class="label-control" for="prefix_sku" style="font-size: 12px" >SKU prefix</label>
                                                    <input type="text"  class="form-control" id="prefix_sku" v-model="prefix_sku">
                                                </div>
                                                <div style="margin-top:8px;">
                                                    <label class="label-control" for="first_sku_id" style="font-size: 12px">First ID</label>
                                                    <input type="text"  class="form-control" id="first_sku_id"  v-model="first_sku_id" >
                                                </div>
                                                <div style="margin-top:8px;">
                                                    <label class="label-control" for="suffix_sku" style="font-size: 12px">SKU suffix</label>
                                                    <input type="text"  class="form-control" id="suffix_sku" v-model="suffix_sku">
                                                </div>
                                            </template>
                                            <template v-else>
                                                <label style="font-weight:bold;color:#00B5B8;">Example: K28ICH0</label>
                                            </template>
                                        </div>
                                        <div class="col-md-3">
                                            <template v-if="sku_number_type=='consecutive'">
                                                <label style="font-weight:bold;color:#00B5B8;">Example: {{((prefix_sku!=null)?prefix_sku:"")+''+((first_sku_id!=null)?first_sku_id:"")+''+((suffix_sku!=null)?suffix_sku:"")}}</label>
                                            </template>
                                            
                                        </div>
                                    </div>
                                    <h5 class="form-section"><i class="fa fa-inbox"></i> Options</h5>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4 offset-md-1 offset-lg-1 col-xl-3 offset-xl-1">
                                            <div class="form-group" style="margin-bottom:10px;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" v-model="opt">
                                                    <span class="input-group-btn">
                                                        <button @click="addOption()" class="btn btn-primary" type="button">
                                                            <i class="ft-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <!-- <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span> -->
                                            </div>
                                        </div>
                                        <div v-if="current_options.length > 0" class="col-md-6 col-lg-5 col-xl-4 offset-md-1 offset-lg-1 offset-xl-1">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0" >
                                                    <thead>
                                                        <tr>
                                                            <th style="border-top: none;text-align: left;padding-top:0;">Options Name</th>
                                                            <th style="text-align: right;border-top: none;padding-top:0;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(option, index) in current_options" :key="index">
                                                            <td class="text-truncate" style="text-align: left;text-transform:capitalize;vertical-align:middle;">{{option.label}}</td>
                                                            <td class="text-truncate" style="text-align: right;">
                                                                <a id="removeOption" @click="removeOption(option.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- <div v-if="current_options.length > 0" class="col-md-6 col-lg-5 col-xl-4 offset-md-1 offset-lg-1 offset-xl-1">
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0" >
                                                    <tbody>
                                                        <tr v-for="(option, index) in current_options" :key="index">
                                                            <td style="border-color:#fff;padding:5px;width:50%;text-transform:capitalize;">{{option.label}}</td>
                                                            <td class="text-truncate" style="border-color:#fff;padding:5px;">
                                                                <a @click="removeOption(option.id)" style="color: red;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> -->
                                    </div>
                                    <h5 class="form-section"><i class="ft-box"></i> Products</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="margin:0;padding-right:4px;color:#777;font-weight:400;">Show items with quantity on hand:</label>
                                            <select id="qoh" class="select2" style="font-size:12px;width:200px;">
                                                <option value="0" selected>Anyone</option>
                                                <option value="1" >More than 0</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-actions right">
                                        <div class="text-right">
                                            <button @click="save()" class="btn btn-secondary" type="button">Save</button>
                                        </div>
                                    </div>
                                </form>
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
    export default {
        mixins:[SettingServices],
        props:['inventory','options'],
        data() {
            return {
                sku_number_type: 'random',
                prefix_sku:'',
                first_sku_id:'',
                suffix_sku:'',
                current_options:[],
                opt: '',
                qoh: 0
            }
        },
        methods: {
            save(){
                var params = {
                    key: 'inventory',
                    data:{
                        sku:{
                            sku_number_type: this.sku_number_type,
                        },
                        options: this.current_options,
                        qoh: this.qoh
                    }
                };
                if(this.sku_number_type == 'consecutive'){
                        params.data.sku.prefix_sku = this.prefix_sku;
                        params.data.sku.first_sku_id = this.first_sku_id;
                        params.data.sku.suffix_sku = this.suffix_sku;
                       
                }

                 $.LoadingOverlay("show");
                this.updateSettings(params, this.updateSettingsCallback);
            },
            updateSettingsCallback(response){
                if(response.status >0){
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    this.searchOptionsCall({page:1, limit:20},this.searchOptionsCallback);
                }else{
                    swal('Error', response.errors[0], 'error');
                    $.LoadingOverlay("hide");
                }
            },
            searchOptionsCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    this.current_options = response.data;
                    swal("Success!", 'Set up inventory settings successfully', "success");
                }else{
                    swal('Error', response.errors[0], 'error');
                }
            },
            addOption(){
                if(this.opt != ''){
                    this.current_options.push({
                        id:0,
                        label: this.opt
                    });
                    this.opt = '';
                }
            },
            removeOption(id){
                var _this = this;
                swal({
                    title: "Are you sure?",
                    text: "This action will remove the option",
                    icon: "warning",
                    buttons: true,

                    buttons: {
                        cancel: {
                            text: "Cancel",
                            value: null,
                            visible: true,
                            className: "btn-warning",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Yes, delete it",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                }).then(isConfirm => {
                    if (isConfirm) {
                        _this.removeOptionCall(id, _this.removeOptionCallback);
                    }
                });
            },
            removeOptionCallback(response){
                if(response.status > 0){
                    for(var i in this.current_options){
                        if(this.current_options[i].id == response.data.id){
                            this.current_options.splice(i, 1);
                        }
                    }
                    swal({ title: "Deleted!", text: response.message, timer: 1000, icon: "success" });
                }else{
                    swal("Error!", response.errors[0], "error");
                }
            }
        },
        created() {
            if(this.inventory){
                var inventory = JSON.parse(this.inventory.data);
                if(inventory.sku.sku_number_type == 'consecutive'){
                    this.sku_number_type = inventory.sku.sku_number_type;
                    this.prefix_sku = inventory.sku.prefix_sku;
                    this.first_sku_id = inventory.sku.first_sku_id;
                    this.suffix_sku = inventory.sku.suffix_sku;
                }
            }
            if(this.options){
                this.current_options = this.options; 
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $("#qoh").select2({
                    minimumResultsForSearch: -1,
                    matcher: self.matchCustom
                });

                $('#qoh').on('change', function (e) {
                    self.qoh = $('#qoh').val();
                });
                if(self.inventory){
                    var inventory = JSON.parse(self.inventory.data);
                    if(inventory.qoh){
                        self.qoh = inventory.qoh;
                        $("#qoh").val(self.qoh).trigger('change');
                    }
                }
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