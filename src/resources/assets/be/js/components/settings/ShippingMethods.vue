<template>
    <div>

        <div id="accordionWrap1" role="tablist" aria-multiselectable="true">
            <div class="card collapse-icon accordion-icon-rotate">
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-secondary btn-min-width mr-1 mb-1" data-toggle="modal" data-target="#shippingMethod"
                            style="float: right">
                            <i class="fa fa-plus"></i>
                            Add carrier
                        </button>
                    </div>
                </div>
                <div id="heading11" class="card-header">
                    <a data-toggle="collapse" data-parent="#accordionWrap1" href="#shipping" aria-expanded="false" aria-controls="accordion11"
                        class="card-title lead collapsed">Shipping Methods</a>
                </div>
                <div id="shipping" role="tabpanel" aria-labelledby="heading11" class="collapse">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li v-if="current_shipping_methods.length > 0" v-for="(shipping_method, index) in current_shipping_methods" :key="index"
                                            class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img v-if="shipping_method.name == 'ups'" :src="'/images/carriers/' + shipping_method.name + '.png'" style="width: 25%; height: auto;">
                                                    <img v-else :src="'/images/carriers/' + shipping_method.name + '.jpg'" style="width: 25%; height: auto;">
                                                    <b>{{shipping_method.label}}</b>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="float-right" style="padding: 5px; display:inline;">
                                                        <a @click="removeShippingMethod(shipping_method.id)" class="btn btn-danger" style="color: #fff;margin-top:2px; margin-bottom:2px;">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </div>
                                                    <span class="float-right">
                                                        <div class="skin skin-square">
                                                            <div class="form-group text-right">
                                                                <fieldset style="padding-top: 10px; margin-right: 15px">
                                                                    <input type="checkbox" :id="'enabled' + shipping_method.id" :data-index="index">
                                                                    <label :for="'enabled' + shipping_method.id">Enabled</label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="shippingMethod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Add carrier</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-4 label-control" style="margin-top: 5px">Name:</label>
                            <div class="col-md-8">
                                <input type="text" v-model="shipping_method.name" v-bind:class="{'input-error' : error.name}" class="form-control" id="name">
                                <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="addShippingMethod()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SettingServices from '../../services/SettingServices.js';
    export default {
        mixins: [SettingServices],
        props: ['shipping_methods'],
        data() {
            return {
                shipping_method: {
                    name: ''
                },
                current_shipping_methods: [],
                error: {
                    name: false
                },
                error_message: {
                    name: ''
                }
            }
        },
        watch: {
            'shipping_method.name'(val) {
                if (val != '') {
                    this.error.name = false;
                    this.error_message.name = '';
                }
            }
        },
        methods: {
            addShippingMethod() {
                if (this.shipping_method.name != '') {
                    $.LoadingOverlay("show");
                    this.addShippingMethodCall(this.shipping_method, this.addShippingMethodCallBackHandler)
                } else {
                    this.error.name = true;
                    this.error_message.name = 'Name is required';
                }
            },
            addShippingMethodCallBackHandler(code, data) {
                if (code == '200') {
                    $('#shippingMethod').modal('hide');
                    this.current_shipping_methods.push(data.data);
                    $.LoadingOverlay("hide");
                }

            },
            enableShippingMethod(id) {
                if (id > 0) {
                    $.LoadingOverlay("show");
                    this.enableShippingMethodCall(id, this.enableShippingMethodCallBackHandler)
                }
            },
            disableShippingMethod(id) {
                if (id > 0) {
                    $.LoadingOverlay("show");
                    this.disableShippingMethodCall(id, this.disableShippingMethodCallBackHandler)
                }
            },
            enableShippingMethodCallBackHandler() {
                $.LoadingOverlay("hide");
            },
            disableShippingMethodCallBackHandler() {
                $.LoadingOverlay("hide");
            },
            removeShippingMethod(id) {
                if (id > 0) {
                    $.LoadingOverlay("show");
                    this.removeShippingMethodCall(id, this.removeShippingMethodCallBackHandler)
                }
            },
            removeShippingMethodCallBackHandler(code, data) {
                if (code == '200') {
                    if (this.current_shipping_methods.length > 0) {
                        for (var i in this.current_shipping_methods) {
                            if (this.current_shipping_methods[i].id == data.data) {
                                this.current_shipping_methods.splice(i, 1);
                            }
                        }
                    }
                }
                $.LoadingOverlay("hide");
            },
            initializeCeckBoxes() {
                var self = this;
                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
                if (self.current_shipping_methods.length > 0) {
                    for (var i in self.current_shipping_methods) {
                        $('#enabled' + self.current_shipping_methods[i].id).on('ifChecked', function (event) {
                            self.enableShippingMethod(self.current_shipping_methods[$(event.target).data('index')].id);
                        });
                        $('#enabled' + self.current_shipping_methods[i].id).on('ifUnchecked', function (event) {
                            self.disableShippingMethod(self.current_shipping_methods[$(event.target).data('index')].id);
                        });
                        self.current_shipping_methods[i].enabled == 1 ? $('#enabled' + self.current_shipping_methods[i].id).iCheck('check') : $('#enabled' + self.current_shipping_methods[i].id).iCheck('chuncheckeck');
                    }
                }
            }
        },
        created() {
            if (this.shipping_methods.length > 0) {
                this.current_shipping_methods = this.shipping_methods;
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.initializeCeckBoxes();
            });
        },
        updated() {
            this.initializeCeckBoxes();
        }
    }
</script>
<style>
    .input-error {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }
</style>