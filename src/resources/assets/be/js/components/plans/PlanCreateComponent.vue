<template>
    <div>
        <div class="card custom-card">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="card-body div-dimensions">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h4 class="form-section">Add Plan</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Title</b>
                                </label>
                                <input type="text" v-model="current_plan.title" class="form-control" v-bind:class="{'input-error-select' : error.title}">
                                <span v-if="error.title" class="message-error">{{error_message.title}}</span>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Payment Amount</b>
                                </label>
                                <input type="text" v-model="current_plan.price" class="form-control" v-bind:class="{'input-error-select' : error.price}">
                                <span v-if="error.price" class="message-error">{{error_message.price}}</span>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <label class="label-custom" for="interval_count">
                                    <b>Interval</b>
                                    </label>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" name="interval_count" v-model="current_plan.interval_count"  v-bind:class="{'input-error-select' : error.interval_count}">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary text-capitalize dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{current_plan.interval}}
                                        </button>
                                        <div class="dropdown-menu">
                                            <a v-for="(interval, index) in intervals" :key="index" class="dropdown-item text-capitalize" @click="current_plan.interval = interval">{{interval}}</a>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="error.interval_count" class="message-error">{{error_message.interval_count}}</span>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="status">
                                    <b>Status</b>
                                </label>
                                <select id="status" class="select2 form-control" style="width: 100%" v-model="current_plan.status" name="status">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Description</b>
                                </label>
                                <textarea type="text" v-model="current_plan.description" class="form-control" rows="6"></textarea>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="basicInput">
                                    <b>Discount type</b>
                                </label>
                                <select id="discount_type" v-model="discount.discount_type" class="select2 form-control" style="width: 100%">
                                    <option v-for="(type, index) in discount_types" :key="index" :value="type.value">{{type.label}}</option>
                                </select>
                            </fieldset>
                        </div>
                        <div v-if="discount.discount_type != 'none'" class="col-md-6">
                            <fieldset class="form-group">
                                <label><b>Discount value</b></label>
                                <div v-if="discount.discount_type == 'percentage'" class="input-group">
                                    <input type='number' v-model="discount.discount_value" id="discount_value" class="form-control" min="0" max="100" v-bind:class="{'input-error-select' : error.discount_value}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                                <div v-else-if="discount.discount_type == 'fixed_amount'" class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type='number' v-model="discount.discount_value" id="discount_value" class="form-control" min="0" max="100" placeholder="0.00" step="0.01" v-bind:class="{'input-error-select' : error.discount_value}">
                                </div>
                                <span v-if="error.discount_value" class="message-error">{{error_message.discount_value}}</span>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" v-if="discount.discount_type != 'none'">
                        <div class="col-md-6">
                            <label><b>Minimum requirement</b></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <select id="min_requirement" v-model="discount.min_requirement" class="select2 form-control" style="width: 100%">
                                        <option value="none">None</option>
                                        <option value="purchase_amount">Minimum purchase amount</option>
                                        <option value="qty_items">Minimum quantity of items</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" style="align-self:center;">
                            <div v-if="discount.min_requirement == 'purchase_amount'" style="width:200px;">
                                <!-- <label>Amount</label> -->
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type='number' v-model="discount.requirement_amount" placeholder="Amount" id="requirement_amount" class="form-control" min="0" max="100"
                                        v-bind:class="{'input-error-select' : error.requirement_amount}"/>
                                </div>
                                <span v-if="error.requirement_amount" class="message-error">{{error_message.requirement_amount}}</span>
                                <!-- <p v-if="discount.apply_to == 'order' && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies to entire order.</p>
                                <p v-if="discount.apply_to == 'products'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected products.</p>
                                <p v-if="discount.apply_to == 'categories'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected categories.</p> -->
                            </div>
                            <div v-if="discount.min_requirement == 'qty_items'" style="width:200px;">
                                <!-- <label>Quantity</label> -->
                                <div class="input-group">
                                    <input type='number' v-model="discount.requirement_amount" placeholder="Quantity" id="requirement_amount" class="form-control" min="0" max="100" 
                                        v-bind:class="{'input-error-select' : error.requirement_amount}"/>
                                </div>
                                <span v-if="error.requirement_amount" class="message-error">{{error_message.requirement_amount}}</span>
                                <p v-if="discount.apply_to == 'order'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies to entire order.</p>
                                <p v-if="discount.apply_to == 'products'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected products.</p>
                                <p v-if="discount.apply_to == 'categories'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected categories.</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 label-control" for="projectinput1" style="text-align:left!important;"><b>Products</b></label>
                        <div class="col-md-3" style="margin-bottom:5px;">
                            <div class="input-group">
                                <input type="text" v-model="item_qty" class="form-control" placeholder="Quantity">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                <select id="select2-items" class="select2-placeholder form-control" style="width: 100%;">
                                </select>
                                <span class="input-group-btn">
                                    <button @click="addItem()" class="btn btn-primary" type="button">
                                        <i class="ft-plus"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-if="items_list.length > 0" class="row">
                        <div class="col-md-12">
                            <p style="margin-bottom:4px; font-size:15px;"><strong>Items list</strong></p>
                            <ul class="list-group">
                                <template >
                                    <li v-for="(product, key) in items_list" class="list-group-item" :key="key" style="border:none; padding:0.5rem 0;">
                                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                                        <div class="row">
                                            <div class="col-md-6" style="align-self:center;">
                                                {{product.name}} ({{product.qty}})
                                            </div>
                                            <div class="col-md-3" style="align-self:center;">
                                                $ {{product.price}}
                                            </div>
                                            <div class="col-md-3 text-right" style="align-self:center;">
                                                <div style="padding: 5px; display:inline;">
                                                    <a @click="removeItem(key)" title="Remove item">
                                                        <i class="fa fa-remove d-none d-sm-inline" style="color:red;font-size:18px;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 10px">
                            <button v-if="editable_plan || current_plan.id >0" @click="saveUpdatePlan()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                                Update
                            </button>
                            <button v-else @click="saveUpdatePlan()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                                Save
                            </button>
                            <button @click="cancelPlan()" class="btn btn-danger" type="button" style="float: right">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PlanServices from '../../services/PlanServices';
    import Helpers from '../../../../../../misc/helpers.js';
    export default {
        mixins: [PlanServices,Helpers],
        props: ['editable_plan'],
        data() {
            return {
                moment:moment,
                current_plan:{
                    id: '',
                    title: '',
                    description: '',
                    price: 0.00,
                    interval_count: '1',
                    interval: 'month',
                    status: 'public',
                },
                error: {
                    title: false,
                    price: false,
                    interval_count: false,
                    discount_value: false,
                    requirement_amount: false
                },
                error_message: {
                    title: '',
                    price:'',
                    interval_count:'',
                    discount_value:'',
                    requirement_amount:''
                },
                intervals:['day','month', 'year'],
                items_list:[],
                item_id:0,
                item_name:'',
                item_price:0.00,
                item_qty:1,
                discount_types:[
                    {
                        "label": "None",
                        "value": "none"
                    },
                    {
                        "label": "Percentage",
                        "value": "percentage"
                    },
                    {
                        "label": "Fixed Amount",
                        "value": "fixed_amount"
                    },
                    {
                        "label": "Free Shipping",
                        "value": "free_shipping"
                    },
                ],
                discount:{
                    id: 0,
                    code: '',
                    discount_type: 'none',
                    discount_value: 0.00,
                    apply_to: 'order',
                    min_requirement: 'none',
                    requirement_amount: '',
                    start_date: moment().format('YYYY-MM-DD HH:mm:ss'),
                }
            }
        },
        methods: {
            saveUpdatePlan() {
                if (this.current_plan.title != '') {
                    $.LoadingOverlay("show");
                    if(this.items_list.length > 0){
                        this.current_plan.items_list = this.items_list;
                    }
                    this.current_plan.discount = this.discount;
                    if(this.current_plan.id > 0){
                         this.updatePlanCall(this.current_plan.id, this.current_plan, this.saveUpdatePlanCallback);
                    }else{
                         this.savePlanCall(this.current_plan, this.saveUpdatePlanCallback);
                    }
                } else {
                    if (this.current_plan.title == '') {
                        this.error.title = true;
                        this.error_message.title = 'Plan title is required';
                    }
                    // if (this.current_plan.price == '') {
                    //     this.error.price = true;
                    //     this.error_message.price = 'Plan price is required';
                    // }
                }

            },
            saveUpdatePlanCallback(response) {
                if(response.status > 0){
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    this.current_plan.id = response.data.id;
                    toastr.success(response.message, 'Create/Update Plan', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                }else{
                    if(this.isAssoc(response.errors) > 0){
                        for(let field in response.errors){
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', response.errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
            cancelPlan() {
                window.location.href = "/admin/store/plans"
            },
             addItem(){
                if(this.item_id >0 && this.item_name != '' && this.item_qty > 0){
                    var addData = true;
                    for(var i in this.items_list){
                        if(this.items_list[i].id == this.item_id){
                            if(this.items_list[i].qty != this.item_qty){
                                this.items_list[i].qty = this.item_qty;
                            }
                            addData = false;
                            break;
                        }
                    }
                    if(addData){
                        this.items_list.push({
                            id: this.item_id,
                            name: this.item_name,
                            price: 0.00,
                            qty: this.item_qty
                        });
                    }
                    this.item_id = 0;
                    this.item_name = '';
                    this.item_price = 0.00;
                    this.item_qty = 1;
                    $('#select2-items').val(null).trigger('change');
                }
            },
            removeItem(index){
                this.items_list.splice(index,1);
            },
            initItems(){
                var self = this;
                $("#select2-items").select2({
                    placeholder: 'Select items',
                    ajax: {
                        url: '/api/admin/items/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            return {
                                query: params.term + '*', // search term
                                fields: ['id', 'name','price'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: response.data,
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.name) {
                            self.item_id = repo.id;
                            self.item_name = repo.name;
                            self.item_price = repo.price;
                            return repo.name
                        } else {
                            return repo.text;
                        }
                    }
                });
            },
            appendData(response){
                var data = response.data;
                data.unshift({id: 0, title: 'None'});
                return data;
            },
        },
        watch: {
            'current_plan.title'(val) {
                if (val != '') {
                    this.error.title = false;
                    this.error_message.title = '';
                }
            },
            // 'current_plan.price'(val) {
            //     if (typeof val == 'number') {
            //         this.error.price = false;
            //         this.error_message.price = '';
            //     }
            // }
        },
        created() {
            if (this.editable_plan) {
                this.current_plan.id = this.editable_plan.id;
                this.current_plan.title = this.editable_plan.title;
                this.current_plan.description = this.editable_plan.description;
                this.current_plan.price = parseFloat(this.editable_plan.price).toFixed(2);
                this.current_plan.interval_count = this.editable_plan.interval_count;
                this.current_plan.interval = this.editable_plan.interval;
                this.current_plan.status = this.editable_plan.status;

                if(this.editable_plan.plan_items.length > 0){
                    for(var i in this.editable_plan.plan_items){
                        this.items_list.push(this.editable_plan.plan_items[i]);
                    }
                }

                if(this.editable_plan.discount){
                    this.discount.id = this.editable_plan.discount.id;
                    this.discount.discount_type = this.editable_plan.discount.discount_type;
                    this.discount.discount_value = this.editable_plan.discount.discount_value;
                    this.discount.min_requirement = this.editable_plan.discount.min_requirement;
                    this.discount.requirement_amount = this.editable_plan.discount.requirement_amount;
                }
            }
        },
        mounted() {
            var _this = this;
            this.$nextTick(function(){
                _this.initItems();
            });
        }
    }
</script>
<style>
    .summer_n {
        text-align: center;
    }

    .preview-thumb {
        width: 70%;
        height: auto;
    }

    .input-error-select {
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