<template>
    <div>
        <div id="form-action-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Invoicing</h4>
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
                                    <h5 class="form-section"><i class="fa fa-barcode"></i> Order Number</h5>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <fieldset class="form-group">
                                                <fieldset>
                                                    <label class="custom-control custom-radio">
                                                        <input id="order_random" v-model="order_number_type" value="random" name="media" :checked="order_number_type=='random'"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">Random</span>
                                                    </label>
                                                </fieldset>
                                                <fieldset>
                                                    <label class="custom-control custom-radio">
                                                        <input id="order_consecutive" v-model="order_number_type" value="consecutive" name="media" type="radio" class="custom-control-input"
                                                        :checked="order_number_type=='consecutive'">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description">Consecutive</span>
                                                    </label>
                                                </fieldset>

                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <template v-if="order_number_type=='consecutive'">
                                                <div>
                                                    <label class="label-control" for="prefix_order" style="font-size: 12px" >Order number prefix</label>
                                                    <input type="text"  class="form-control" id="prefix_order" v-model="prefix_order">
                                                </div>
                                                <div style="margin-top:8px;">
                                                    <label class="label-control" for="first_order_id" style="font-size: 12px">First ID</label>
                                                    <input type="text"  class="form-control" id="first_order_id"  v-model="first_order_id" >
                                                </div>
                                                <div style="margin-top:8px;">
                                                    <label class="label-control" for="suffix_order" style="font-size: 12px">Order number suffix</label>
                                                    <input type="text"  class="form-control" id="suffix_order" v-model="suffix_order">
                                                </div>
                                            </template>
                                            <template v-else>
                                                <label style="font-weight:bold;color:#00B5B8;">Example: K28ICH0</label>
                                            </template>
                                        </div>
                                        <div class="col-md-3">
                                            <template v-if="order_number_type=='consecutive'">
                                                <label style="font-weight:bold;color:#00B5B8;">Example: {{((prefix_order!=null)?prefix_order:"")+''+((first_order_id!=null)?first_order_id:"")+''+((suffix_order!=null)?suffix_order:"")}}</label>
                                            </template>
                                            
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
        props:['invoicing'],
        data() {
            return {
                order_number_type: 'random',
                prefix_order:'',
                first_order_id:'',
                suffix_order:'',
            }
        },
        methods: {
            save(){
                var params = {
                    key: 'invoicing',
                    data:{
                        order_number_type: this.order_number_type,
                    }
                    
                };
                if(this.order_number_type == 'consecutive'){
                        params.data.prefix_order = this.prefix_order;
                        params.data.first_order_id = this.first_order_id;
                        params.data.suffix_order = this.suffix_order;
                       
                }

                 $.LoadingOverlay("show");
                this.updateSettings(params, this.updateSettingsCallback);
                
            },
            updateSettingsCallback(response){
                if(response.status >0){
                    $.LoadingOverlay("hide");
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    swal("Success!", response.message, "success");
                }else{
                    swal('Error', response.errors[0], 'error');
                    $.LoadingOverlay("hide");
                }
            }
        },
        created() {
            if(this.invoicing){
                var invoicing = JSON.parse(this.invoicing.data);
                if(invoicing.order_number_type == 'consecutive'){
                    this.order_number_type = invoicing.order_number_type;
                    this.prefix_order = invoicing.prefix_order;
                    this.first_order_id = invoicing.first_order_id;
                    this.suffix_order = invoicing.suffix_order;
                }
            }
        },
        mounted() {
            
            this.$nextTick(function () {
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
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