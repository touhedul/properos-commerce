<template>
    <div>
        <div class="card">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h4 class="form-section">Add Subscription</h4> 
                        </div>
                    </div>
                    <form class="form form-horizontal">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="form-section">
                                        <i class="ft-user"></i> Customer Info</h4>
                    <!-- <div class="row">
                        <div class="col-md-12"> -->
                            <!-- <h4 class="form-section">
                                <i class="ft-user"></i> Customer Info</h4> -->
                                    <div v-if="subscription.id<=0" class="form-group row">
                                        <label class="col-md-12 label-control" for="projectinput1" style="text-align:left!important;"><b>Search customer</b></label>
                                        <div class="col-md-12">
                                            <div class="input-group" v-bind:class="{'input-error-select' : error.customer}">
                                                <select id="select2-customer" required class="select2-placeholder form-control" data-placeholder="Select customer..." style="width: 100%">
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newCustomer">
                                                        <i class="ft-user"></i>
                                                        <span class="d-none d-md-inline-block">Add customer</span>
                                                    </button>
                                                </span>
                                            </div>
                                            <span v-if="error.user" class="message-error">{{error_message.user}}</span>
                                        </div>
                                    </div>
                                    <div v-if="user.avatar || user.email || user.phone" class="form-group row">
                                        <div v-if="user.avatar" class="col-md-3 offset-md-2" style="padding: 5px">
                                            <span class="avatar avatar-online" style="width: 80px; height: auto;">
                                                <img :src="user.avatar">
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div v-if="user.name" class="col-md-12">
                                                    <label class="label-control" for="projectinput2" style="font-size: 12px">Full name</label>
                                                    <input type="text" v-model="user.name" class="form-control" id="name" disabled>
                                                </div>
                                                <div v-if="user.email" class="col-md-12">
                                                    <label class="label-control" for="projectinput2" style="font-size: 12px">Email</label>
                                                    <input type="email" v-model="user.email" class="form-control" id="email" disabled>
                                                </div>
                                                <div v-if="user.phone" class="col-md-12">
                                                    <label class="label-control" for="projectinput2" style="font-size: 12px">Phone</label>
                                                    <input type="text" v-model="user.phone" class="form-control" id="phone" disabled>
                                                </div>
                                                <div v-if="user.company" class="col-md-12">
                                                    <label class="label-control" for="projectinput2" style="font-size: 12px">Company</label>
                                                    <input type="text" v-model="user.company" class="form-control" id="customer_company" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="user.avatar || user.email || user.phone" class="form-group row" style="margin-bottom:0;">
                                        <div class="col-md-10 offset-md-2">
                                            <div class="card-body" style="padding-bottom:0;">
                                                <div class="skin skin-square">
                                                    <div class="form-group">
                                                        <fieldset>
                                                            <input type="checkbox" id="automatic_payments">
                                                            <label for="automatic_payments">Automatic Payments</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="automatic_payments" class="form-group row"> 
                                        <div class="col-md-12">
                                            <h4 class="form-section">
                                                <i class="fa fa-money"></i> Payment Info</h4>
                                            <payment-container :not_show_cash="true" :user="user" :card_processor="card_processor" :client_key="client_key" :api_id="api_id" @sendPaymentId="paymentIdHandle" @newPaymentAdded="newPaymentAddedHandle"></payment-container> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="form-section">
                                        <i class="fa fa-file"></i> Plan Details</h4>
                        <div v-show="choosePlan" class="form-group row">
                            <label class="col-md-12 label-control" for="projectinput1" style="text-align:left!important;"><b>Select plan</b></label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select id="select2-plans" class="select2-placeholder form-control" style="width: 100%">
                                        <option value="0">None</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div v-if="current_plan.id>0 || (current_plan.title != '' && current_plan.price != '')" class="form-group row">
                            <div class="col-md-12" style="margin-top:20px;">
                            </div>
                            <div class="col-md-8 label-control" style="text-align:left!important;">
                                <label for="projectinput1" >
                                    <b>{{current_plan.title}} - 
                                    <span v-if="current_plan['price'] >= 0">$ <strong>{{current_plan['price']}}</strong> <small>/{{interval[current_plan.interval]}}</small></span>
                                </b></label>
                                <label>
                                    <span v-if="current_plan.discount.discount_type && current_plan.discount.discount_type != 'none'"> 
                                    <strong>Discount - </strong> 
                                    <span v-if="current_plan.discount.discount_type == 'percentage'"> <strong>:</strong> %{{current_plan.discount.discount_value}} off</span>
                                    <span v-if="current_plan.discount.discount_type == 'fixed_amount'"> <strong>:</strong> ${{current_plan.discount.discount_value}} off</span>
                                    </span>
                                </label>
                                <label>
                                    <span v-if="current_plan.discount.min_requirement && current_plan.discount.min_requirement != 'none'"> 
                                    <strong>Minimum requirement </strong> 
                                    <span v-if="current_plan.discount.min_requirement == 'purchase_amount'"> <strong>:</strong> ${{current_plan.discount.requirement_amount}}</span>
                                    <span v-if="current_plan.discount.min_requirement == 'qty_items'"> <strong>:</strong> {{current_plan.discount.requirement_amount}} items</span>
                                    </span>
                                </label>
                            </div>
                            <div v-show="choosePlan" class="col-md-4 text-right" style="align">
                                <button @click="customPlan()" class="btn btn-primary" type="button">
                                    Customize plan
                                </button>
                            </div>
                            <div v-show="!choosePlan" class="col-md-4 text-right" style="align">
                                <button @click="selectPlan()" class="btn btn-primary" type="button">
                                    Select plan
                                </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Next billing date</b></label>
                                <input type='text' v-model="subscription.next_billing_date" id="next_billing_date" class="form-control" style="border-color: #D9D9D9!important;" />
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Next payment date</b></label>
                                <input type='text' v-model="subscription.next_payment_date" id="next_payment_date" class="form-control" placeholder="Select a date" style="border-color: #D9D9D9!important;" disabled/>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Days to Pay</b></label>
                                <input type='text' v-model="subscription.days_to_pay" class="form-control" style="border-color: #D9D9D9!important;"/>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Grace Days</b></label>
                                <input type='text' v-model="subscription.grace_days" class="form-control" style="border-color: #D9D9D9!important;"/>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Expiration date</b></label>
                                <input type='text' v-model="subscription.expires_at" class="form-control" style="border-color: #D9D9D9!important;" disabled/>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="label-control"><b>Status</b></label>
                                <div>
                                    <div class="input-group">
                                        <select id="status" v-model="subscription.status" class="select2 form-control" style="width: 100%;border-color: #D9D9D9!important;">
                                            <option value="trial">Trial</option>
                                            <option value="active">Active</option>
                                            <option value="expired">Expired</option>
                                            <option value="locked">Locked</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-3">
                                <label class="label-control"><b>Payment Amount</b></label>
                                <div class="input-group">
                                    <input type="text" v-model="current_plan.price" class="form-control" style="border-color: #D9D9D9!important;" :disabled="custom_plan">
                                </div>
                                <span v-if="error.price" class="message-error">{{error_message.price}}</span>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <fieldset>
                                    <label class="label-custom" for="interval_count"><b>Interval</b></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control text-right" name="interval_count" v-model="current_plan.interval_count"  v-bind:class="{'input-error-select' : error.interval_count}" style="border-color: #D9D9D9!important;" :disabled="custom_plan">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary text-capitalize dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="custom_plan">
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
                            <div class="col-sm-12 col-md-3">
                                <label class="label-control" style="text-align:left!important;"><b>Total Payments</b></label>
                                <input type='text' v-model="subscription.total_payments" class="form-control" style="border-color: #D9D9D9!important;"/>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="label-control" for="projectinput1" style="text-align:left!important;"><b>Payments Made</b></label>
                                <input type='text' v-model="subscription.payments_made" class="form-control" style="border-color: #D9D9D9!important;" disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="form-section">
                                    <i class="ft-box"></i> Products</h4>
                                <div class="form-group row" v-show="!custom_plan && current_plan.id == 0">
                                    <div class="col-md-3">
                                        <label class="label-control" for="projectinput5">Category</label>
                                        <div class="input-group" v-bind:class="{'input-error-select' : error.category}">
                                            <select id="select2-category" class="select2-placeholder form-control"  data-placeholder="Select category..." style="width:100%">
                                                <!-- <option v-for="(category, key) in current_categories" :key="key" :value="category.id">{{category.name}}</option> -->
                                            </select>
                                        </div>
                                        <span v-if="error.category" class="message-error">{{error_message.category}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="label-control" for="projectinput6">Product</label>
                                        <div class="input-group" v-bind:class="{'input-error-select' : error.item}">
                                            <select id="select2-product" class="select2-placeholder form-control"  data-placeholder="Select product..." style="width:100%">
                                                <!-- <option v-for="(product, key) in selectables_products" :key="key" :value="product.id">{{product.name}}</option> -->
                                            </select>
                                        </div>
                                        <span v-if="error.item" class="message-error">{{error_message.item}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="label-control">Quantity</label>
                                        <div class="input-group" v-bind:class="{'input-error-select' : error.qty}" style="width: 50%">
                                            <input type="number" min="1" v-model="item_qty" class="form-control" id="quantity"  step="0.01">
                                        </div>
                                        <span v-if="error.qty" class="message-error">{{error_message.qty}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="label-control">&nbsp;</label>
                                        <button @click="addItem()" type="button" class="btn btn-secondary btn-min-width mr-1 mb-1" style="float: right; margin-top:22px;">
                                            <i class="fa fa-plus"></i> Add item
                                        </button>
                                    </div>
                                </div>
                                
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul v-if="current_plan.plan_items.length > 0 && custom_plan" class="list-group">
                                            <li v-for="(order_product, index) in current_plan.plan_items" class="list-group-item" :key="index">
                                                <div class="row" v-if=" current_plan.plan_items[index].id > 0">
                                                    <div class="col-md-2 text-center">
                                                        <span class="">
                                                            <a href="#">
                                                                <img v-if="order_product.item && order_product.item.files && order_product.item.files.length > 0" :src="'/storage/'+order_product.item.files[0].thumb_path" style="width: auto; height: 120px" />
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Name:</label>
                                                            <div style="width: 80%">
                                                                <!-- <div style="font-size: 20px">{{order_product.name}}</div> -->
                                                                <input type="text" v-model="current_plan.plan_items[index].name" disabled class="form-control">
                                                                <span v-if="order_product.options && !Array.isArray(order_product.options)">
                                                                    (<span v-for="(opt, index, i) in order_product.options" :key="index" style="text-transform:capitalize;font-size:12px;">
                                                                        {{paintOptions(index, opt, i)}}
                                                                    </span>)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Qty:</label>
                                                            <div style="width: 80%">
                                                                <input disabled type="number" min="1" v-model="current_plan.plan_items[index].qty" class="form-control" step="0.01"
                                                                    
                                                                    id="quantity" placeholder="qty"
                                                                    style="width: 100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Regular price:</label>
                                                            <div style="width: 80%">
                                                                <input type="text"  disabled v-model="current_plan.plan_items[index].price" class="form-control" style="width: 100%" >
                                                            </div>
                                                            <template v-if="order_product.discount_percent > 0"> 
                                                                <div>Discount percent:
                                                                    <b>{{order_product.discount_percent}}%
                                                                    </b>
                                                                </div>
                                                                <div>Discounted price:
                                                                    <b>
                                                                        <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order_product.price*1 - (order_product.price * order_product.discount_percent/100)*1,2)}}</b>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <ul v-if="subscription.items_list.length > 0 && !custom_plan && current_plan.id == 0" class="list-group">
                                            <li v-for="(order_product, index) in subscription.items_list" class="list-group-item" :key="index">
                                                <div class="row" v-if=" subscription.items_list[index].id > 0">
                                                    <div class="col-md-2 text-center">
                                                        <span class="">
                                                            <a href="#">
                                                                <img v-if="order_product.item && order_product.item.files && order_product.item.files.length > 0 && order_product.item.files[0].thumb_path" :src="'/storage/'+order_product.item.files[0].thumb_path" style="width: auto; height: 120px" />
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Name:</label>
                                                            <div style="width: 80%">
                                                                <!-- <div style="font-size: 20px">{{order_product.name}}</div> -->
                                                                <input type="text" v-model="subscription.items_list[index].name" class="form-control" >
                                                                <span v-if="order_product.options && !Array.isArray(order_product.options)">
                                                                    (<span v-for="(opt, index, i) in order_product.options" :key="index" style="text-transform:capitalize;font-size:12px;">
                                                                        {{paintOptions(index, opt, i)}}
                                                                    </span>)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Qty:</label>
                                                            <div style="width: 80%">
                                                                <input @input="updateMonthlyPayment(index)" type="number" min="1" v-model="subscription.items_list[index].qty" class="form-control" step="0.01"
                                                                    
                                                                    id="quantity" placeholder="qty"
                                                                    style="width: 100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group row">
                                                            <label style="width: 100%">Regular price:</label>
                                                            <div style="width: 80%">
                                                                <input type="text"  @input="updateMonthlyPayment()" v-model="subscription.items_list[index].price" class="form-control" style="width: 100%" >
                                                            </div>
                                                            <template v-if="order_product.discount_percent > 0"> 
                                                                <div>Discount percent:
                                                                    <b>{{order_product.discount_percent}}%
                                                                    </b>
                                                                </div>
                                                                <div>Discounted price:
                                                                    <b>
                                                                        <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order_product.price*1 - (order_product.price * order_product.discount_percent/100)*1,2)}}</b>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label style="width: 100%"></label>
                                                        <button type="button" @click="removeItem(index)" class="btn btn-danger float-right">
                                                            <i class="fa fa-trash"></i>
                                                            <span class="d-none d-xl-inline">Remove item</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Additional notes for this subscription</label>
                                            <textarea name="notes" v-model="subscription.notes" id="description" rows="5" class="form-control" placeholder="Type some notes...">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 10px">
                            <button v-if="editable_subscription || subscription.id >0" @click="saveUpdateSubscription()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                                Update
                            </button>
                            <button v-else @click="saveUpdateSubscription()" class="btn btn-primary" type="button" style="float: right; margin-left: 5px">
                                Save
                            </button>
                            <button @click="cancelSubscription()" class="btn btn-danger" type="button" style="float: right">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade text-left show" id="newCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel1">Add customer</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">*Name:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="customer.name" v-bind:class="{'input-error-select' : error.name}" class="form-control" id="name">
                                    <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">*Last name:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="customer.lastname" v-bind:class="{'input-error-select' : error.lastname}" class="form-control"
                                        id="lastname">
                                    <span v-if="error.lastname" class="message-error">{{error_message.lastname}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">*Email:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="customer.email" v-bind:class="{'input-error-select' : error.email}" class="form-control" id="email">
                                    <span v-if="error.email" class="message-error">{{error_message.email}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Phone:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="customer.phone" class="form-control" id="phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Company:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="customer.company" class="form-control" id="company">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                            <button @click="addCustomer()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriptionServices from '../../services/SubscriptionServices.js';
    import Helpers from '../../../../../../misc/helpers.js';
    import PaymentContainer from '../payments/PaymentContainerComponent.vue';
    export default {
        components:{
            'payment-container': PaymentContainer
        },
        mixins: [SubscriptionServices,Helpers],
        props: ['editable_subscription','card_processor','client_key','api_id','settings'],
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
                    plan_items:[],
                    discount:{
                        discount_type: 'none'
                    },
                },
                error: {
                    user: false,
                    price: false,
                    interval_count: false,
                },
                error_message: {
                    user: '',
                    price:'',
                    interval_count:''
                },
                interval:{
                    'year':'yr',
                    'month':'mo',
                },
                selectCustomer: true,
                user:{
                    id:0,
                    name:'',
                    lastname:'',
                    email:'',
                    phone:'',
                    avatar:'',
                    company:''
                },
                customer:{
                    id:0,
                    name:'',
                    lastname:'',
                    email:'',
                    phone:'',
                    avatar:'',
                    company:''
                },
                item_id:0,
                item_name:'',
                item_price:0.00,
                item_qty:1,
                subscription:{
                    id:'',
                    items_list:[],
                    status: 'active',
                    started_trial_at:'',
                    last_plan_id:'',
                    next_plan_id:'',
                    last_payment:0.00,
                    last_payment_date:'',
                    next_payment_date: moment().add(1,'days').format('MM/DD/YYYY hh:mm A'),
                    next_billing_date: moment().add(1,'days').format('MM/DD/YYYY hh:mm A'),
                    payment_profile_id:0,
                    days_to_pay: (this.settings && this.settings.days_to_pay) ? this.settings.days_to_pay : 7,
                    grace_days: 7,
                    expires_at:'',
                    total_payments:0,
                    payments_made: 0,
                    notes:''
                },
                price:0.00,
                intervals:['day','month', 'year'],
                discount_type:{
                    'percentage': 'Percentage',
                    'fixed_amount': 'Fixed amount',
                    'free_shipping': 'Free shipping'
                },
                custom_plan: false,
                choosePlan: true,
                automatic_payments:false,
                automatic_payments_history: false,
                new_profile : false,
                order_item: {
                    id: '',
                    order_id: '',
                    name: '',
                    description: '',
                    price: 0.00,
                    price_discount: 0.00,
                    discount_percent: 0.00,
                    item_id: '',
                    category_id: '',
                    qty: '',
                    sku: '',
                    thumb_path: '',
                    total_product_amount: 0.00
                },
            }
        },
        methods: {
            customPlan(){
                this.custom_plan = false;
                this.choosePlan = false;
                this.current_plan.title = 'Custom';
                this.current_plan.id = 0;
                if(this.current_plan.plan_items.length > 0){
                    for(var i in this.current_plan.plan_items){
                        this.subscription.items_list.push(this.current_plan.plan_items[i]);
                    }
                }
            },
            selectPlan(){
                this.choosePlan = true;
                let data = {
                    name: 'None',
                    id: 0
                };
                var option = new Option(data.name, data.id, true, true);
                $("#select2-plans").append(option).trigger('change');
                $("#select2-plans").trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            },
            paymentIdHandle(value){
                this.subscription.payment_profile_id = value;
            },
            newPaymentAddedHandle(value){
                // this.subscription.payment_profile_id = value;
                this.automatic_payments = false;
                this.new_profile = true;
                this.getUserInfo(this.user.id);
            },
            updateMonthlyPayment() {
                this.current_plan.price = 0.00;
                for(var i in this.subscription.items_list){
                    this.current_plan.price = (this.current_plan.price*1 + this.subscription.items_list[i].price * 1*this.subscription.items_list[i].qty).toFixed(); 
                }
            },
            saveUpdateSubscription() {
                if (this.user.id > 0 && this.current_plan.price > 0) {
                    $.LoadingOverlay("show");
                    this.subscription.current_plan_id = this.current_plan.id;
                    this.subscription.user_id = this.user.id;
                    this.subscription.interval_count = this.current_plan.interval_count;
                    this.subscription.interval = this.current_plan.interval;
                    this.subscription.price = this.current_plan.price;
                     this.subscription.current_plan_id = this.current_plan.id;
                    this.subscription.next_payment_date = moment(this.subscription.next_payment_date, 'MM/DD/YYYY hh:mm A').format('YYYY-MM-DD HH:mm:ss');
                    this.subscription.next_billing_date = moment(this.subscription.next_billing_date, 'MM/DD/YYYY hh:mm A').format('YYYY-MM-DD HH:mm:ss');
                    if(this.subscription.id > 0){
                         this.updateSubscriptionCall(this.subscription, this.saveUpdateSubscriptionCallback);
                    }else{
                         this.saveSubscriptionCall(this.subscription, this.saveUpdateSubscriptionCallback);
                    }
                } else {
                    if (this.user.id == 0) {
                        this.error.user = true;
                        this.error_message.user = 'User is required';
                    }
                    if (this.price == 0) {
                        this.error.price = true;
                        this.error_message.price = 'Price is required';
                    }
                }
            },
            saveUpdateSubscriptionCallback(response) {
                if(response.status > 0){
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    this.subscription.id = response.data.id;
                    this.subscription.next_payment_date = moment(this.subscription.next_payment_date, 'YYYY-MM-DD HH:mm:ss').format('MM/DD/YYYY hh:mm A');
                    this.subscription.next_billing_date = moment(this.subscription.next_billing_date, 'YYYY-MM-DD HH:mm:ss').format('MM/DD/YYYY hh:mm A');
                    toastr.success(response.message, 'Create/Update Subscription', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
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
            initCustomer() {
                var self = this;
                $("#select2-customer").select2({
                    placeholder: 'Select customer...',
                    ajax: {
                        url: '/api/admin/users/search',
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
                                fields: ['id', 'firstname', 'lastname'],
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
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.firstname + " " + repo.lastname + "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if (repo.firstname && repo.lastname) {
                            self.user.id = repo.id;
                            self.getUserInfo(self.user.id);
                            return repo.firstname + " " + repo.lastname;
                        } else {
                            return repo.text;
                        }
                    } 
                });
            },
            initPlans() {
                var self = this;
                $("#select2-plans").select2({
                    ajax: {
                        url: '/api/plans/search',
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
                                fields: ['id', 'title'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: self.appendData(response),
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.title + "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if(repo.id > 0){
                            self.getPlanInfo(repo.id);
                            self.custom_plan = true;
                        }else{
                            self.current_plan.id =0;
                            self.custom_plan = false;
                        }
                        if (repo.title) {
                            return repo.title;
                        }else {
                            return repo.text;
                        }
                    } // omitted for brevity, see the source of this page
                });
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
            getUserInfo(id) {
                if (id) {
                    $.LoadingOverlay("show");
                    $('#automatic_payments').iCheck('uncheck');
                    this.automatic_payments = false;
                    var _this = this;
                    axios({
                        method: 'get',
                        url: '/api/my-account/basic-user-info/' + id,
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            _this.user = response.data.data;
                            _this.user.name = response.data.data.firstname+' '+response.data.data.lastname;
                            _this.user.avatar = response.data.data.avatar ? '/storage/' + response.data.data.avatar : '/images/avatar-placeholder.png';

                            $('#automatic_payments').iCheck({
                                checkboxClass: 'icheckbox_square-red',
                                radioClass: 'iradio_square-red',
                            });
                            

                            $('#automatic_payments').on('ifChecked', function (event) {
                                _this.automatic_payments = true;
                            });

                            $('#automatic_payments').on('ifUnchecked', function (event) {
                                _this.automatic_payments = false;
                                _this.subscription.payment_profile_id = 0;
                            });

                            if(this.new_profile){
                                $('#automatic_payments').iCheck('check');
                                _this.new_profile = false;
                                setTimeout(function(){
                                    $('.payment_profile'+_this.subscription.payment_profile_id).iCheck('check');
                                },100)
                            }

                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            getPlanInfo(id) {
                if (id) {
                    $.LoadingOverlay("show");
                    axios({
                        method: 'get',
                        url: '/api/plans/info/' + id,
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            this.current_plan.id = response.data.data.id;
                            this.current_plan.title = response.data.data.title;
                            this.current_plan.description = response.data.data.description;
                            this.current_plan.price = response.data.data.price;
                            this.current_plan.interval_count = response.data.data.interval_count;
                            this.current_plan.interval = response.data.data.interval;
                            this.current_plan.status = response.data.data.status;
                            if(response.data.data.plan_items.length > 0){
                                this.current_plan.plan_items = response.data.data.plan_items;
                            }else{
                                this.current_plan.plan_items = [];
                            }

                            if(response.data.data.discount){
                                this.current_plan.discount.id = response.data.data.discount.id;
                                this.current_plan.discount.discount_type = response.data.data.discount.discount_type;
                                this.current_plan.discount.discount_value = response.data.data.discount.discount_value;
                                this.current_plan.discount.min_requirement = response.data.data.discount.min_requirement;
                                this.current_plan.discount.requirement_amount = response.data.data.discount.requirement_amount;
                            }else{
                                this.current_plan.discount = {
                                    discount_type: 'none'
                                }
                            }
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            addItem(){
                if(this.item_id >0 && this.item_name != '' && this.item_qty > 0){
                    var addData = true;
                    for(var i in this.subscription.items_list){
                        if(this.subscription.items_list[i].id == this.item_id){
                            if(this.subscription.items_list[i].qty != this.item_qty){
                                this.subscription.items_list[i].qty = this.item_qty;
                            }
                            addData = false;
                            break;
                        }
                    }
                    if(addData){
                        this.subscription.items_list.push({
                            id: this.item_id,
                            name: this.item_name,
                            price: this.item_price,
                            qty: this.item_qty
                        });
                    }
                    this.item_id = 0;
                    this.item_name = '';
                    this.item_price = 0.00;
                    this.item_qty = 1;

                    this.updateMonthlyPayment();
                    $('#select2-items').val(null).trigger('change');
                }
            },
            removeItem(index){
                this.subscription.items_list.splice(index,1);
                if(this.choosePlan){
                    this.current_plan.price = 0.00;
                    for(var i in this.subscription.items_list){
                        this.current_plan.price = (this.current_plan.price*1 + this.subscription.items_list[i].price * 1*this.subscription.items_list[i].qty).toFixed(); 
                    }
                }
            },
            resetCustomerInfo() {
                var self = this;
                Object.keys(self.customer).forEach(function (key) {
                    self.customer[key] = '';
                });
            },
            resetCustomerErrors() {
                var self = this;
                Object.keys(self.customer).forEach(function (key) {
                    self.customer[key] = false;
                });
            },
            addCustomer() {
                var self = this;
                var modal_ref = $('#newCustomer');
                if (this.validateCustomerInput()) {
                    var customer = Object.assign({}, this.customer);
                    $.LoadingOverlay("show");
                    axios({
                        method: 'post',
                        url: '/api/admin/add-customer',
                        data: customer
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            self.user.id = response.data.data.id;
                            self.user.email = response.data.data.email;
                            self.user.company = response.data.data.company;
                            self.user.phone = response.data.data.phone;
                            self.user.firstname = response.data.data.firstname;
                            self.user.lastname = response.data.data.lastname;
                            self.user.avatar = response.data.data.avatar ? '/storage/' + response.data.data.avatar : '/images/avatar-placeholder.png';
                            // self.current_customers.push(response.data.data);

                            let data = {
                                name: response.data.data.firstname + " " + response.data.data.lastname,
                                id: response.data.data.id
                            };
                            var option = new Option(data.name, data.id, true, true);
                            $("#select2-customer").append(option).trigger('change');
                            $("#select2-customer").trigger({
                                type: 'select2:select',
                                params: {
                                    data: data
                                }
                            });
                            modal_ref.modal('hide');

                            setTimeout(function(){
                                $('#automatic_payments').iCheck({
                                    checkboxClass: 'icheckbox_square-red',
                                    radioClass: 'iradio_square-red',
                                });
                                
    
                                $('#automatic_payments').on('ifChecked', function (event) {
                                    self.automatic_payments = true;
                                });
    
                                $('#automatic_payments').on('ifUnchecked', function (event) {
                                    self.automatic_payments = false;
                                    self.subscription.payment_profile_id = 0;
                                });
                            },100)

                        } else {
                            if(Helpers.isAssoc(response.data.errors) > 0){
                                for(let field in response.data.errors){
                                    this.error_message[field] = response.data.errors[field][0];
                                    this.error[field] = true;
                                }
                            }else{
                                swal("Error!", response.data.errors[0], "error");
                            }
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            validateCustomerInput() {
                if (this.customer.name != '' && this.customer.lastname != '' && this.customer.email != '') {
                    if (Helpers.validateEmail(this.customer.email)) {
                        return true;
                    } else {
                        this.error.email = true;
                        this.error_message.email = 'Email is invalid';
                    }
                }
                else {
                    if (this.customer.name == '') {
                        this.error.name = true;
                        this.error_message.name = 'Customer name is required';
                    }
                    if (this.customer.lastname == '') {
                        this.error.lastname = true;
                        this.error_message.lastname = 'Customer lastname is required';
                    }
                    if (this.customer.email == '') {
                        this.error.email = true;
                        this.error_message.email = 'Customer email is required';
                    }
                }
            },
            initCategories() {
                var self = this;
                $("#select2-category").select2({
                    placeholder: 'Select category...',
                    ajax: {
                        url: '/api/admin/categories/search',
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
                                fields: ['id', 'name'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON response, except to indicate that infinite
                            // scrolling can be used
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
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if (repo.id) {
                            self.order_item.category_id = repo.id;
                        }
                        return repo.name || repo.text;
                    } // omitted for brevity, see the source of this page
                });
            },
            initProducts() {
                var self = this;
                console.log('here')
                $("#select2-product").select2({
                    placeholder: 'Select product...',
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
                            if (self.order_item.category_id && self.order_item.category_id > 0) {
                                return {
                                    query: '+*' + params.term.trim().replace(" ", "* +*") + '*', // search term 
                                    fields: ['id', 'name', 'category_id','sku'],
                                    where: ['category_id', self.order_item.category_id],
                                    page: params.page,
                                    limit: 5
                                };
                            } else {
                                return {
                                    query: '+*' + params.term.trim().replace(" ", "* +*") + '*', // search term
                                    fields: ['id', 'name','sku','price'],
                                    page: params.page,
                                    limit: 5
                                };
                            }

                        },
                        processResults: function (response, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON response, except to indicate that infinite
                            // scrolling can be used
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
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name +""+((repo.sku) ? " ("+repo.sku+")" : "")+ "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if (repo.id) {
                            self.item_id = repo.id;
                            self.item_name = repo.name;
                            self.item_price = repo.price;
                        }
                        return repo.name || repo.text;
                    } // omitted for brevity, see the source of this page
                });
            },
        },
        watch: {
            'current_plan.title'(val) {
                if (val != '') {
                    this.error.title = false;
                    this.error_message.title = '';
                }
            },
            'subscription.days_to_pay'(val){
                // this.subscription.next_billing_date = moment(this.subscription.next_payment_date).add(val,'days').format('MM/DD/YYYY hh:mm A')
                this.subscription.expires_at = moment(this.subscription.next_payment_date).add(val*1+this.subscription.grace_days*1,'days').format('MM/DD/YYYY hh:mm A')
            },
            'subscription.grace_days'(val){
                this.subscription.expires_at = moment(this.subscription.next_payment_date).add(val*1+this.subscription.days_to_pay*1,'days').format('MM/DD/YYYY hh:mm A')
            }
            // 'current_plan.price'(val) {
            //     if (typeof val == 'number') {
            //         this.error.price = false;
            //         this.error_message.price = '';
            //     }
            // }
        },
        created() {
            if (this.editable_subscription) {
                this.subscription.id = this.editable_subscription.id;
                this.subscription.payment_profile_id = this.editable_subscription.payment_profile_id;
                this.subscription.status = this.editable_subscription.status;
                this.subscription.total_payments = this.editable_subscription.total_payments;
                this.subscription.payments_made = this.editable_subscription.payments_made;
                this.subscription.days_to_pay = this.editable_subscription.days_to_pay;
                this.subscription.next_payment_date = (this.editable_subscription.next_payment_date) ? moment(this.editable_subscription.next_payment_date, 'YYYY-MM-DD HH:mm:ss').format('MM/DD/YYYY hh:mm A') :  moment().add(1,'days').format('MM/DD/YYYY hh:mm A');
                this.subscription.next_billing_date = (this.editable_subscription.next_billing_date) ? moment(this.editable_subscription.next_billing_date, 'YYYY-MM-DD HH:mm:ss').format('MM/DD/YYYY hh:mm A') :  moment().add(1,'days').format('MM/DD/YYYY hh:mm A');
                this.current_plan.id = this.editable_subscription.current_plan_id;
                this.current_plan.price = (this.editable_subscription.price) ? parseFloat(this.editable_subscription.price).toFixed(2) : 0.00;
                this.current_plan.interval_count = (this.editable_subscription.interval_count) ? this.editable_subscription.interval_count : 1;
                this.current_plan.interval = (this.editable_subscription.interval) ? this.editable_subscription.interval : 'month';

                if(this.editable_subscription.user){
                    this.user = this.editable_subscription.user;
                    this.user.id = this.editable_subscription.user.id;
                    this.user.name = this.editable_subscription.user.firstname+' '+this.editable_subscription.user.lastname;
                    // this.user.email = this.editable_subscription.user.email;
                    this.user.avatar = this.editable_subscription.user.avatar ? '/storage/' + this.editable_subscription.user.avatar : '/images/avatar-placeholder.png';
                }

                if(this.editable_subscription.subscription_items && this.editable_subscription.subscription_items.length > 0){
                    for(var i in this.editable_subscription.subscription_items){
                        this.subscription.items_list.push(this.editable_subscription.subscription_items[i]);
                    }
                }
                console.log(this.subscription.items_list);
                if(this.current_plan.id == 0){
                    this.custom_plan =true;
                    this.choosePlan = false;
                    this.current_plan.title = 'Custom';
                }else{
                    this.custom_plan =false;
                    this.choosePlan = true;
                    var _this = this;
                    this.$nextTick(function(){
                        let data = {
                            name: _this.editable_subscription.data.title,
                            id: _this.editable_subscription.data.id
                        };
                        var option = new Option(data.name, data.id, true, true);
                        $("#select2-plans").append(option).trigger('change');
                        $("#select2-plans").trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                        if(_this.editable_subscription.data.discount){
                            _this.current_plan.discount.id = _this.editable_subscription.data.discount.id;
                            _this.current_plan.discount.discount_type = _this.editable_subscription.data.discount.discount_type;
                            _this.current_plan.discount.discount_value = _this.editable_subscription.data.discount.discount_value;
                            _this.current_plan.discount.min_requirement = _this.editable_subscription.data.discount.min_requirement;
                            _this.current_plan.discount.requirement_amount = _this.editable_subscription.data.discount.requirement_amount;
                        }
                    });
                    
                }
            }else{
                // if(this.settings && this.settings){
                //     this.subscription.days_to_pay = 
                // }
            }

        },
        mounted() {
            var _this = this;
            this.$nextTick(function(){
                _this.initCustomer();
                _this.initPlans();
                _this.initItems();
                _this.initCategories();
                _this.initProducts();

                $('#next_billing_date').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    _this.subscription.next_billing_date = e.date.format('MM/DD/YYYY hh:mm A');
                    _this.subscription.next_payment_date =  _this.subscription.next_billing_date;
                     _this.subscription.expires_at = moment(_this.subscription.next_billing_date).add(_this.subscription.days_to_pay,'days').format('MM/DD/YYYY hh:mm A')
                });

                _this.subscription.expires_at = moment(_this.subscription.next_billing_date).add(_this.subscription.days_to_pay*1+_this.subscription.grace_days*1,'days').format('MM/DD/YYYY hh:mm A')

                // $('#select2-customer').on('change', function (e) {
                //     self.user.id = $('#select2-customer').select2('data').length > 0 ? parseInt($('#select2-customer').select2('data')[0].id) : '';
                //     self.getUserInfo(self.order.customer_id);
                // });

                $('#automatic_payments').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                if(this.subscription.payment_profile_id > 0){
                    $('#automatic_payments').iCheck('check');
                     _this.automatic_payments = true;
                     setTimeout(function(){
                         if(_this.editable_subscription.user && _this.subscription.payment_profile_id
                            && _this.editable_subscription.user.customer_profile && _this.editable_subscription.user.customer_profile.payment_profiles){
                                $(".payment_profile"+_this.subscription.payment_profile_id).iCheck('check');
                            }
                     },50);
                }

                $('#automatic_payments').on('ifChecked', function (event) {
                    _this.automatic_payments = true;
                });

                $('#automatic_payments').on('ifUnchecked', function (event) {
                    _this.automatic_payments = false;
                    _this.subscription.payment_profile_id = 0;
                });

                $('#newCustomer').on('hidden.bs.modal', function () {
                    _this.resetCustomerInfo();
                    _this.resetCustomerErrors();
                });

                $('#newCustomer').on('show.bs.modal', function () {
                    _this.resetCustomerInfo();
                    // _this.resetCurrentUserInfo();
                });
                
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
    .card-body {
        min-height: unset!important;
    }

    .div-dimensions{
        min-width: 320px;
        max-width: 794px;
    }

    .card-header{
        padding-top: 0;
    }
</style>