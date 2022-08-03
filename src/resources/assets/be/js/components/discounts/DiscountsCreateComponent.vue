<template>
    <div>
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card" style="height: 100% !important;">
                        <div class="card-header">
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
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <h4 class="form-section">Add discount</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <span style="float:right;">
                                                    <a href="#" @click="generateCode()">Generate code</a>
                                                </span>
                                                <label for="userinput1">Discount Code</label>
                                                <input type="text" v-model="discount.code" name="discount" id="discount" class="form-control" v-bind:class="{'input-error-select' : error.code}"
                                                    placeholder="e.g. FALLSALE">
                                                <span v-if="!error.code" style="font-size:11px;">Customer will enter the discount code at checkout.</span>
                                                <span v-if="error.code" class="message-error">{{error_message.code}}</span>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 mb-2">
                                            <label for="discount_type">Discount type</label>
                                            <div class="input-group">
                                                <select id="discount_type" v-model="discount.discount_type" class="select2 form-control" style="width: 100%">
                                                    <option v-for="(type, index) in discount_types" :key="index" :value="type.value">{{type.label}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid rgba(0, 0, 0, 0.43)">
                                    <div class="row" >
                                        <div v-if="discount.discount_type == 'buy_x_get_y'" class="col-md-12">
                                            <p><strong>Customer buys</strong></p>
                                        </div>
                                        <div v-if="discount.discount_type != 'free_shipping'" class="col-md-3 mb-2">
                                            <div v-if="discount.discount_type != 'buy_x_get_y'" class="form-group">
                                                <label >Discount value</label>
                                                <div v-if="discount.discount_type == 'percentage'" class="input-group">
                                                    <input type='number' v-model="discount.discount_value" id="discount_value" class="form-control" min="0" max="100" v-bind:class="{'input-error-select' : error.discount_value}"/>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                                <div v-else-if="discount.discount_type == 'fixed_amount'" class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <input type='number' v-model="discount.discount_value" id="discount_value" class="form-control" min="0" max="100" placeholder="0.00" step="0.01" v-bind:class="{'input-error-select' : error.discount_value}">
                                                </div>
                                                <span v-if="error.discount_value" class="message-error">{{error_message.discount_value}}</span>
                                            </div>
                                            <div v-else class="form-group">
                                                <label>Quantity</label>
                                                <div class="input-group">
                                                    <input type='number' v-model="discount.buy_qty" id="buy_qty" class="form-control" v-bind:class="{'input-error-select' : error.buy_qty}">
                                                </div>
                                                <span v-if="error.buy_qty" class="message-error">{{error_message.buy_qty}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label>Applies to</label>
                                                <div class="input-group">
                                                    <select id="apply_to" v-model="discount.apply_to" class="select2 form-control" style="width: 100%">
                                                        <option value="order" v-if="discount.discount_type != 'buy_x_get_y'">Entire order</option>
                                                        <option value="order" v-if="discount.discount_type == 'buy_x_get_y'">All products</option>
                                                        <option value="products">Specific products</option>
                                                        <option value="categories">Specific categories</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="discount.apply_to != 'order'" class="col-md-5" style="align-self:center;">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="select2-applicables" class="select2-placeholder form-control" style="width: 100%">
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button @click="addApplicable()" class="btn btn-primary" type="button">
                                                            <i class="ft-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="discount.applicables_list.length > 0" class="row">
                                        <div class="col-md-12">
                                            <p style="margin-bottom:4px; font-size:15px;"><strong>Applicables list</strong></p>
                                            <ul class="list-group">
                                                <template >
                                                    <li v-for="(product, key) in discount.applicables_list" class="list-group-item" :key="key" style="border:none; padding:0.5rem 0;">
                                                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                                                        <div class="row">
                                                            <div class="col-md-9" style="align-self:center;">
                                                                {{product.name}}
                                                            </div>
                                                            <div class="col-md-3 text-right" style="align-self:center;">
                                                                <div style="padding: 5px; display:inline;">
                                                                    <a @click="removeApplicable(key)" title="Remove applicable">
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
                                    <hr v-if="discount.discount_type == 'buy_x_get_y'">
                                    <div v-if="discount.discount_type == 'buy_x_get_y'" class="row" >
                                        <div class="col-md-12">
                                            <p><strong>Customer gets</strong></p>
                                            <p style="font-size:11px;">Customers must add the quantity of items specified below to their cart.</p>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <div class="input-group">
                                                    <input type='number' v-model="discount.get_qty" id="get_qty" class="form-control" v-bind:class="{'input-error-select' : error.get_qty}"/>
                                                </div>
                                                <span v-if="error.get_qty" class="message-error">{{error_message.get_qty}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="align-self:center;">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="get_what" v-model="discount.get_what" class="select2 form-control" style="width: 100%">
                                                       <option value="same">Same product</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="discount.get_what != 'same'" class="col-md-5" style="align-self:center;">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="select2-get-products" class="select2-placeholder form-control" style="width: 100%">
                                                    </select>
                                                </div>
                                                <span v-if="error.get_what" class="message-error">{{error_message.get_what}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>AT A DISCOUNTED VALUE</strong></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <fieldset>
                                                        <label class="custom-control custom-radio">
                                                            <input id="discount_value" v-model="discounted_value" value="percentage" name="discount_value" :checked="discounted_value =='percentage'"
                                                                type="radio" class="custom-control-input">
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description">Percentage</span>
                                                        </label>
                                                    </fieldset>
                                                    <fieldset>
                                                        <label class="custom-control custom-radio">
                                                            <input id="discount_value" v-model="discounted_value" value="free" name="discount_value" type="radio" class="custom-control-input">
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description">Free</span>
                                                        </label>
                                                    </fieldset>
                                                </div>
                                                <div v-if="discounted_value == 'percentage'"  class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type='number' v-model="discount.discount_value" id="discount_value" class="form-control" max="100" v-bind:class="{'input-error-select' : error.discount_value}"/>
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                        <span v-if="error.discount_value" class="message-error">{{error_message.discount_value}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px solid rgba(0, 0, 0, 0.43)">
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <div class="form-group">
                                                <label>Minimum requirement</label>
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
                                                <p v-if="discount.apply_to == 'order' && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies to entire order.</p>
                                                <p v-if="discount.apply_to == 'products'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected products.</p>
                                                <p v-if="discount.apply_to == 'categories'  && !error.requirement_amount" style="font-size:11px;margin-bottom:0;">Applies only to selected categories.</p>
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
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <div class="form-group">
                                                <label>Eligible customer</label>
                                                <div class="input-group">
                                                    <select id="eligible_customers" v-model="discount.eligible_customers" class="select2 form-control" style="width: 100%">
                                                       <option value="everyone">Everyone</option>
                                                        <option value="specific">Specific customers</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="discount.eligible_customers != 'everyone'" class="col-md-5" style="align-self:center;">
                                            <div class="form-group">
                                                <!-- <label v-if="discount.eligible_customers == 'specific'">Add customers</label> -->
                                                <div class="input-group">
                                                    <select id="select2-customers" required class="select2-placeholder form-control" style="width: 100%">
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button @click="addCustomers()" class="btn btn-primary" type="button">
                                                            <i class="ft-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <span v-if="error.eligible_customers" class="message-error">{{error_message.eligible_customers}}</span>
                                            </div>
                                        </div>
                                    </div>
                                     <div v-if="discount.customers_list.length > 0" class="row">
                                        <div class="col-md-12">
                                            <p style="margin-bottom:4px; font-size:15px;"><strong>Customers list</strong></p>
                                            <ul class="list-group">
                                                <template >
                                                    <li v-for="(customer, key) in discount.customers_list" class="list-group-item" :key="key" style="border:none; padding:0.5rem 0;">
                                                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                                                        <div class="row">
                                                            <div class="col-md-9" style="align-self:center;">
                                                                {{customer.name}} {{customer.lastname}}
                                                            </div>
                                                            <div class="col-md-3 text-right" style="align-self:center;">
                                                                <div style="padding: 5px; display:inline;">
                                                                    <a @click="removeCustomer(key)" title="Remove customer">
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
                                    <hr v-if="discount.customers_list.length > 0" style="border-top: 1px solid rgba(0, 0, 0, 0.43)">
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <div class="form-group">
                                                <label>Eligible locations</label>
                                                <div class="input-group">
                                                    <select id="eligible_locations" v-model="discount.eligible_locations" class="select2 form-control" style="width: 100%">
                                                       <option value="any">Any location</option>
                                                        <option value="specific">Specific location</option>
                                                    </select>
                                                </div>
                                                <span v-if="error.eligible_locations" class="message-error">{{error_message.eligible_locations}}</span>
                                            </div>
                                        </div>
                                        <div v-if="discount.eligible_locations != 'any'" class="col-md-5" style="align-self:center;">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#locations">
                                                <i class="fa fa-plus"></i>
                                                <span>Specific Location</span>
                                            </button>
                                        </div>
                                    </div>
                                     <div class="row" v-if="discount.locations_list.length > 0">
                                        <div class="col-md-12">
                                            <p style="margin-bottom:4px;"><strong>Locations list</strong></p>
                                            <ul class="list-group">
                                                <template >
                                                    <li v-for="(location, key) in discount.locations_list" class="list-group-item" :key="key" style="border:none; padding:0.5rem 0;">
                                                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                                                        <div class="row">
                                                            <div class="col-md-9" style="align-self:center;">
                                                                {{(location.country && location.country.name && location.country.name != '') ? location.country.name: ''}}
                                                                {{(location.state && location.state.name && location.state.name != '') ? ', '+location.state.name: ''}}
                                                                {{(location.city && location.city.name && location.city.name != '') ? ', '+location.city.name: ''}}
                                                            </div>
                                                            <div class="col-md-3 text-right" style="align-self:center;">
                                                                <div style="padding: 5px; display:inline;">
                                                                    <a @click="removeLocation(key)" title="Remove customer">
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
                                    <hr style="border-top: 1px solid rgba(0, 0, 0, 0.43)">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Usage limits</strong></p>
                                            <fieldset>
                                                <div class="skin skin-square">
                                                    <div class="form-group">
                                                        <fieldset>
                                                            <input type="checkbox" id="usage_limit">
                                                            <label for="usage_limit">Limit number of times this discount can be used in total</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div v-if="show_usage_limit" style="width:150px;display:inline-block;">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type='number' v-model="discount.usage_limit" class="form-control" max="100" v-bind:class="{'input-error-select' : error.usage_limit}"/>
                                                        </div>
                                                        <span v-if="error.usage_limit" class="message-error">{{error_message.usage_limit}}</span>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="skin skin-square">
                                                    <div class="form-group">
                                                        <fieldset>
                                                            <input type="checkbox" id="user_limit">
                                                            <label for="user_limit">Limit to one use per customer</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <p><strong>Active dates</strong></p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start date</label>
                                                <input type='text' v-model="discount.start_date" id="start_date" class="form-control" placeholder="Select start date" v-bind:class="{'input-error-select' : error.start_date}"/>
                                                <span v-if="error.start_date" class="message-error">{{error_message.start_date}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="align-self:center;text-align:center;">
                                            <div class="skin skin-square">
                                                <div class="form-group">
                                                    <fieldset>
                                                        <input type="checkbox" id="set_end_date">
                                                        <label for="set_end_date">Set end date</label>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="show_end_date" class="col-md-4">
                                            <div class="form-group">
                                                <label>End date</label>
                                                <input type='text' v-model="discount.end_date" id="end_date" class="form-control" placeholder="Select end date" v-bind:class="{'input-error-select' : error.end_date}"/>
                                                <span v-if="error.end_date" class="message-error">{{error_message.end_date}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-right" style="margin-top: 15px">
                                        <div class="col-12">
                                            <a href="/admin/store/discounts" class="btn btn-warning mr-1">
                                                <i class="ft-x"></i> Cancel
                                            </a>
                                            <template v-if="discount.id > 0">
                                                <button @click="addUpdateDiscount(discount.id)" type="button" class="btn btn-primary">
                                                    <i class="fa fa-check-square-o"></i>
                                                    <span>Update</span>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button @click="addUpdateDiscount()" type="button" class="btn btn-primary">
                                                    <i class="fa fa-check-square-o"></i>
                                                    <span>Save</span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="locations" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Select a location</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select id="countries" v-model="temp_location.country.code" class="select2 form-control" style="width: 100%">
                                <option value="any">Any country</option>
                                <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                            </select>
                        </div>
                        <div class="form-group" v-show="temp_location.country.code != 'any'">
                            <div v-show="temp_location.country && temp_location.country.code == 'US'">
                                <select id="states" class="select2 form-control"  v-model="temp_location.state.abbr" 
                                    style="width: 100%" data-placeholder="Select state...">
                                    <option value="any">Any state</option>
                                    <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                </select>
                            </div>
                            <div class="input-group" v-show="temp_location.country && temp_location.country.code != 'US'">
                                <input type="text" class="form-control" id="state" v-model="temp_location.state.name" placeholder="State name">
                            </div>
                        </div>
                        <div class="form-group" v-if="temp_location.state.abbr != 'any' && temp_location.country.code != 'any' && temp_location.state.name != ''">
                            <div class="input-group">
                                <input type="text" class="form-control" id="city" v-model="temp_location.city.name" placeholder="City name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="addLocation()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add location</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Helpers from '../../../../../../misc/helpers.js';
    import DiscountServices from '../../services/DiscountServices';
    export default {
        props: ['editable_discount'],
        mixins: [Helpers, DiscountServices],
        data() {
            return {
                // current_categories: this.categories,
                discount : {
                    id: 0,
                    code: '',
                    discount_type: 'percentage',
                    discount_value: 0.00,
                    buy_qty: '',
                    apply_to: 'order',
                    get_qty:'',
                    get_what:'same',
                    min_requirement: 'none',
                    requirement_amount: '',
                    eligible_customers:'everyone',
                    eligible_locations:'any',
                    usage_limit:'',
                    user_limit: 0,
                    usage_count:'',
                    start_date: '',
                    end_date: '',
                    applicables_list:[],
                    get_list:[],
                    customers_list:[],
                    locations_list:[],
                },
                discount_types:[
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
                    {
                        "label": "Buy X Get Y",
                        "value": "buy_x_get_y"
                    }
                ],
                discounted_value: 'percentage',
                show_usage_limit: false,
                start_date_datepicker:'',
                end_date_datepicker:'',
                show_end_date: false,
                applicable_id:0,
                applicable_name:'',
                customer_id:0,
                customer_name:'',
                customer_lastname:'',
                countries: [],
                states: [],
                temp_location:{
                    country: {
                        code:'any',
                        name:''
                    },
                    state: {
                        abbr: 'any',
                        name:''
                    },
                    city:{
                        name:''
                    }
                },

                error: {
                    code: false,
                    discount_value: false,
                    apply_to: false,
                    buy_qty: false,
                    get_qty: false,
                    get_what: false,
                    requirement_amount: false,
                    eligible_customers: false,
                    eligible_locations: false,
                    usage_limit: false,
                    start_date: false,
                    end_date: false,
                },
                error_message: {
                    code: '',
                    discount_value: '',
                    apply_to: '',
                    buy_qty: '',
                    get_qty: '',
                    get_what: '',
                    requirement_amount: '',
                    eligible_customers: '',
                    eligible_locations: '',
                    usage_limit: '',
                    start_date: '',
                    end_date: '',
                },
                moment:moment
            }
        },
        methods: {
            generateCode(){
                this.discount.code = (Math.random().toString(36).substr(2, 2).toUpperCase()+''+ btoa(moment().format('YYYY-MM-DD HH:mm:ss')).toUpperCase().substr(18,19)).replace('==','');
            },
            initApplicableList(){
                var url = '';
                var placeholder = '';
                if(this.discount.apply_to == 'products'){
                    url = '/api/admin/items/search';
                    placeholder = 'Select products...';
                }else{
                    url = '/api/admin/categories/search';
                    placeholder = 'Select categories...';
                }
                var self = this;
                this.$nextTick(function(){
                     $("#select2-applicables").select2({
                        placeholder: placeholder,
                        ajax: {
                            url: url,
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
                                self.applicable_id = repo.id;
                                self.applicable_name = repo.name;
                                return repo.name
                            } else {
                                return repo.text;
                            }
                        }
                    });
                })
               
            },
            initGetProduct(){
                var self = this;
                this.$nextTick(function(){
                     $("#select2-get-products").select2({
                        placeholder: 'Specific product...',
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
                                    fields: ['id', 'name'],
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
                                self.discount.get_list = [{
                                    id: repo.id,
                                    name: repo.name
                                }];
                                return repo.name
                            } else {
                                return repo.text;
                            }
                        }
                    });
                })
               
            },
            initCustomerList(){
                var self = this;
                this.$nextTick(function(){
                     $("#select2-customers").select2({
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
                        escapeMarkup: function (markup) { return markup; }, 
                        templateResult: function (repo) {
                            if (repo.loading) return repo.text;
                            var markup = "<div class='select2-result-repository clearfix'>" + repo.firstname +" "+ repo.lastname + "</div>";
                            return markup;
                        }, 
                        templateSelection: function (repo) {
                            if (repo.firstname) {
                                self.customer_id = repo.id;
                                self.customer_name = repo.firstname;
                                self.customer_lastname = repo.lastname;
                                var name = repo.firstname +' '+ repo.lastname;
                                return name;
                            } else {
                                return repo.text;
                            }
                        }
                    });
                })
               
            },
            addApplicable(){
                if(this.applicable_id >0 && this.applicable_name != ''){
                    var addData = true;
                    for(var i in this.discount.applicables_list){
                        if(this.discount.applicables_list[i].id == this.applicable_id){
                            addData = false;
                            break;
                        }
                    }
                    if(addData){
                        this.discount.applicables_list.push({
                            id: this.applicable_id,
                            name: this.applicable_name
                        });
                    }
                    this.applicable_id = 0;
                    this.applicable_name = '';
                    $('#select2-applicables').val(null).trigger('change');
                }
            },
            addCustomers(){
                if(this.customer_id >0 && this.customer_name != ''){
                    var addData = true;
                    for(var i in this.discount.customers_list){
                        if(this.discount.customers_list[i].id == this.customer_id){
                            addData = false;
                            break;
                        }
                    }
                    if(addData){
                        this.discount.customers_list.push({
                            id: this.customer_id,
                            name: this.customer_name,
                            lastname: this.customer_lastname
                        });
                    }
                    this.customer_id = 0;
                    this.customer_name = '';
                    this.customer_lastname = '';
                    $('#select2-customers').val(null).trigger('change');
                }
            },
            removeApplicable(index){
                this.discount.applicables_list.splice(index,1);
            },
            removeCustomer(index){
                this.discount.customers_list.splice(index,1);
            },
            initGetWhat(){
                var self = this;
                this.$nextTick(function(){
                    $('#get_what').on('change', function (e) {
                        $('#get_what').select2();
                        self.discount.get_what = $('#get_what').select2('data')[0].id;
                    
                        if(self.discount.get_what != 'same'){
                            self.initGetProduct();
                        }else{
                            self.discount.get_list = [];
                        }
                    });
                });
            },
            getCountriesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.countries = data;
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
            addLocation(){
                var location = {};
                if(this.temp_location.country.code != 'any'){
                    location['country'] = {
                        code: this.temp_location.country.code,
                        name: this.temp_location.country.name,
                    }
                }
                if(this.temp_location.state.abbr != 'any'){
                    location['state'] = {
                        abbr: this.temp_location.state.abbr,
                        name: this.temp_location.state.name,
                    }
                }else if(this.temp_location.state.abbr == '' && this.temp_location.state.name != ''){
                    location['state'] = {
                        abbr: '',
                        name: this.temp_location.state.name,
                    }
                }
                 if(this.temp_location.city.name != ''){
                    location['city'] = {
                        name: this.temp_location.city.name,
                    }
                }
                this.discount.locations_list.push(location);
                this.temp_location = {
                    country: {
                        code:'any',
                        name:''
                    },
                    state: {
                        abbr: 'any',
                        name:''
                    },
                    city:{
                        name:''
                    }
                };
                 $('#countries').val('any').trigger('change');
                 $('#states').val('any').trigger('change');
            },
            removeLocation(index){
                this.discount.locations_list.splice(index,1);
            },
            addUpdateDiscount(id = 0) {
                var error = false;
                if(this.discount.code == ''){
                    this.error.code = true;
                    this.error_message.code = 'Discount code is required';
                    error = true;
                }else{
                    this.error.code = false;
                }
                if(this.discount.discount_value == 0 && this.discounted_value != 'free' && this.discount.discount_type != 'free_shipping'){
                    this.error.discount_value = true;
                    this.error_message.discount_value = 'Discount value required';
                    error = true;
                }else{
                    this.error.discount_value = false;
                }
                
                if(this.discount.discount_type == 'buy_x_get_y'){
                    if(this.discount.buy_qty == 0 || this.discount.buy_qty == ''){
                        this.error.buy_qty = true;
                        this.error_message.buy_qty = 'Quantity required';
                        error = true;
                    }else{
                        this.error.buy_qty = false;
                    }
                    if(this.discount.get_qty == 0 || this.discount.get_qty == ''){
                        this.error.get_qty = true;
                        this.error_message.get_qty = 'Quantity required';
                        error = true;
                    }else{
                        this.error.get_qty = false;
                    }
                    if(this.discount.get_what != 'same' && this.discount.get_list.length == 0){
                        this.error.get_what = true;
                        this.error_message.get_what = 'Select a product';
                        error = true;
                    }else{
                        this.error.get_what = false;
                    }
                }else{
                    this.error.buy_qty = false;
                    this.error.get_qty = false;
                    this.error.get_what = false;
                }

                if(this.discount.apply_to != 'order' && this.discount.applicables_list.length == 0){
                    this.error.apply_to = true;
                    this.error_message.apply_to = 'Select an option is required';
                    error = true;
                }else{
                    this.error.apply_to = false;
                }

                if(this.discount.min_requirement != 'none' && this.discount.requirement_amount == 0){
                    this.error.requirement_amount = true;
                    if(this.discount.min_requirement == 'purchase_amount')
                        this.error_message.requirement_amount = 'Amount is required';
                    if(this.discount.min_requirement == 'qty_items')
                        this.error_message.requirement_amount = 'Quantity of items is required';
                    error = true;
                }else{
                    this.error.requirement_amount = false;
                }

                if(this.discount.eligible_customers != 'everyone' && this.discount.customers_list.length == 0){
                    this.error.eligible_customers = true;
                    this.error_message.eligible_customers = 'Select a customer';
                    error = true;
                }else{
                    this.error.eligible_customers = false;
                }

                if(this.discount.eligible_locations != 'any' && this.discount.locations_list.length == 0){
                    this.error.eligible_locations = true;
                    this.error_message.eligible_locations = 'Select a location';
                    error = true;
                }else{
                    this.error.eligible_locations = false;
                }

                if(this.show_usage_limit && this.discount.usage_limit == 0){
                    this.error.usage_limit = true;
                    this.error_message.usage_limit = 'Usage limit is required';
                    error = true;
                }else{
                    this.error.usage_limit = false;
                }

                if(this.discount.start_date == ''){
                    this.error.start_date = true;
                    this.error_message.start_date = 'Start date is required';
                    error = true;
                }else{
                    this.error.start_date = false;
                }

                if(this.show_end_date && this.discount.end_date == ''){
                    this.error.end_date = true;
                    this.error_message.end_date = 'End date is required';
                    error = true;
                }else{
                    this.error.end_date = false;
                }
                if(!error){
                    $.LoadingOverlay("show");
                    if(this.discounted_value == 'free'){
                        this.discount.discount_value = 100;
                    }
                    if(!this.show_end_date){
                        this.discount.end_date = null;
                    }
                    if(id == 0){
                        this.storeDiscount(this.discount, this.storeDiscountCallback);
                    }else{
                        this.updateDiscount(id, this.discount, this.updateDiscountCallback);
                    }
                    
                    
                }
            },
            storeDiscountCallback(response){
                if(response.status > 0){
                    this.discount.id = response.data.id;
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    $.LoadingOverlay("hide");
                }else{
                    if(this.isAssoc(response.errors) > 0){
                        for(let field in response.errors){
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', response.data.errors[0], 'error');
                    }
                    $.LoadingOverlay("hide");
                }
            },
            updateDiscountCallback(response){
                if(response.status > 0){
                    this.discount.id = response.data.id;
                    toastr.success('The discount has been successfully updated', 'Discount updated.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    $.LoadingOverlay("hide");
                }else{
                    if(this.isAssoc(response.errors) > 0){
                        for(let field in response.errors){
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', response.data.errors[0], 'error');
                    }
                    $.LoadingOverlay("hide");
                }
            },
        },
        watch: {
            'discount.code'(val){
                if(val != ''){
                    this.error.code = false;
                }
            },
            'discount.discount_value'(val){
                if(val > 0){
                    this.error.discount_value = false;
                }
            }, 
            'discount.applicables_list'(val){
                if(this.discount.applicables_list.length > 0){
                    this.error.apply_to = false;
                }
            },
            'discount.requirement_amount'(val){
                if(val > 0){
                    this.error.requirement_amount = false;
                }
            },
            'discount.customers_list'(val){
                if(this.discount.customers_list.length > 0){
                    this.error.eligible_customers = false;
                }
            },
            'discount.locations_list'(val){
                if(this.discount.locations_list.length > 0){
                    this.error.eligible_locations = false;
                }
            },
            'discount.usage_limit'(val){
                if(val > 0){
                    this.error.usage_limit = false;
                }
            },
            'discount.start_date'(val){
                if(val != ''){
                    this.error.start_date = false;
                }
            },
            'discount.end_date'(val){
                if(val != ''){
                    this.error.end_date = false;
                }
            },
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            if (this.editable_discount) {
                this.discount.id = this.editable_discount.id;
                this.discount.code = this.editable_discount.code;
                this.discount.apply_to = this.editable_discount.apply_to;
                this.discount.buy_qty = this.editable_discount.buy_qty;
                this.discount.get_qty = this.editable_discount.get_qty;
                this.discount.get_what = this.editable_discount.get_what;
                this.discount.discount_type = this.editable_discount.discount_type;
                this.discount.discount_value = this.editable_discount.discount_value;
                this.discount.eligible_customers = this.editable_discount.eligible_customers;
                this.discount.eligible_locations = this.editable_discount.eligible_locations;
                this.discount.start_date = moment(this.editable_discount.start_date, "YYYY-MM-DD HH:mm:ss").format("MM/DD/YYYY hh:mm A");
                this.discount.end_date = (this.editable_discount.end_date) ? moment(this.editable_discount.end_date, "YYYY-MM-DD HH:mm:ss").format("MM/DD/YYYY hh:mm A") : '';
                this.discount.min_requirement = this.editable_discount.min_requirement;
                this.discount.requirement_amount = this.editable_discount.requirement_amount;
                this.discount.usage_count = this.editable_discount.usage_count;

                this.discount.usage_limit = this.editable_discount.usage_limit;
                this.discount.user_limit = this.editable_discount.user_limit;

                if(this.editable_discount.applicables && this.editable_discount.applicables.length > 0){
                    for(var i in this.editable_discount.applicables){
                        if(this.editable_discount.applicables[i].applicable){
                            this.discount.applicables_list.push({
                                id: this.editable_discount.applicables[i].applicable_id,
                                name: this.editable_discount.applicables[i].applicable.name,
                            })
                        }
                    }
                }
                if(this.editable_discount.customers && this.editable_discount.customers.length > 0){
                    for(var i in this.editable_discount.customers){
                        if(this.editable_discount.customers[i].customers){
                            this.discount.customers_list.push({
                                id: this.editable_discount.customers[i].customer_id,
                                name: this.editable_discount.customers[i].customers.firstname + ' '+ this.editable_discount.customers[i].customers.lastname,
                            })
                        }
                    }
                }
                if(this.editable_discount.locations && this.editable_discount.locations.length > 0){
                   
                    for(var i in this.editable_discount.locations){
                         var list = {};
                        if(this.editable_discount.locations[i].country_name != null){
                            list['country'] = {};
                            list['country']['name'] = this.editable_discount.locations[i].country_name;
                            list['country']['code'] = this.editable_discount.locations[i].country_code;
                        }
                        if(this.editable_discount.locations[i].state_name != null){
                            list['state'] = {};
                            list['state']['name'] = this.editable_discount.locations[i].state_name;
                            list['state']['abbr'] = this.editable_discount.locations[i].state_abbr;
                        }
                        if(this.editable_discount.locations[i].city_name != null){
                            list['city'] = {};
                            list['city']['name'] = this.editable_discount.locations[i].city_name;
                        }
                        if(typeof list == 'object'){
                             this.discount.locations_list.push(list)
                        }
                    }
                }

                if(this.discount.discount_type == 'buy_x_get_y' && this.editable_discount.get_item_id > 0){
                    this.discount.get_list.push({
                        id: this.editable_discount.get_item_id,
                        name: this.editable_discount.get_item_name
                    });
                }

                if(this.discount.discount_type == 'buy_x_get_y' && this.editable_discount.discount_value < 100){
                    this.discounted_value = 'percentage';
                }else if (this.discount.discount_type == 'buy_x_get_y' && this.editable_discount.discount_value >= 100){
                    this.discounted_value = 'free';
                }

                var self = this;
                this.$nextTick(function () {
                    self.initApplicableList();
                    self.initGetWhat();
                    self.initCustomerList();
                    if(self.discount.get_list.length > 0){
                        self.initGetProduct();
                        let data = {
                            name: self.editable_discount.get_item_name,
                            id: self.editable_discount.get_item_id
                        };
                        var option = new Option(data.name, data.id, true, true);
                        $("#select2-get-products").append(option).trigger('change');
                        $("#select2-get-products").trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    }
                });
            }
        },
        mounted() {
            this.$nextTick(function () {
                var self = this;
                $(".select2").select2();
                $("#countries").select2();
                $("#states").select2();

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
                this.start_date_datepicker = $('#start_date').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    self.discount.start_date = e.date.format('MM/DD/YYYY hh:mm A');
                });

                this.end_date_datepicker = $('#end_date').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    self.discount.end_date = e.date.format('MM/DD/YYYY hh:mm A');
                });

                // if (this.editable_item) {
                //     this.publish_datepicker.data("DateTimePicker").date(moment(this.editable_item.publish_date).format('MM/DD/YYYY hh:mm A'));
                // }
                //Select to discount type
                $('#discount_type').on('change', function (e) {
                    self.discount.discount_type = $('#discount_type').select2('data')[0].id;
                    if(self.discount.discount_type == 'buy_x_get_y'){
                        //Select to get what fiels
                        self.initGetWhat();
                        
                    }
                });
                //Select to apply to field
                $('#apply_to').on('change', function (e) {
                    var destroy = false;
                    if(self.discount.apply_to == 'products' || self.discount.apply_to == 'categories'){
                        destroy = true;
                    }
                    self.discount.apply_to = $('#apply_to').select2('data')[0].id;
                    self.applicable_id = 0;
                    self.applicable_name = '';
                    self.discount.applicables_list = [];
                    if(self.discount.apply_to != 'order'){
                        if(destroy){
                            $('#select2-applicables').select2('destroy');
                        }
                        self.initApplicableList();
                    }
                });

                if(self.discount.end_date != ''){
                    $('#set_end_date').iCheck('check');
                    self.show_end_date = true;
                }
                
                $('#min_requirement').on('change', function (e) {
                    self.discount.min_requirement = $('#min_requirement').select2('data')[0].id;
                });
                $('#eligible_customers').on('change', function (e) {
                    self.discount.eligible_customers = $('#eligible_customers').select2('data')[0].id;
                    if(self.discount.eligible_customers != 'same'){
                        self.discount.customers_list = [];
                        self.initCustomerList();
                    }
                });
                $('#eligible_locations').on('change', function (e) {
                    self.discount.eligible_locations = $('#eligible_locations').select2('data')[0].id;
                    self.discount.locations_list = [];
                });

                $('#usage_limit').on('ifChecked', function (event) {
                    self.show_usage_limit = true;
                });

                $('#usage_limit').on('ifUnchecked', function (event) {
                    self.show_usage_limit = false;
                    self.discount.usage_limit = null;
                });

                $('#user_limit').on('ifChecked', function (event) {
                    self.discount.user_limit = 1;
                });

                $('#user_limit').on('ifUnchecked', function (event) {
                    self.discount.user_limit = 0;
                });

                if(self.discount.usage_limit > 0){
                    $("#usage_limit").iCheck('check');
                }
                
                if(self.discount.user_limit > 0){
                    $("#user_limit").iCheck('check');
                }

                $('#set_end_date').on('ifChecked', function (event) {
                    self.show_end_date = true;
                });

                $('#set_end_date').on('ifUnchecked', function (event) {
                    self.show_end_date = false;
                    self.discount.end_date = null;
                });

                
                 $('#locations').on('shown.bs.modal', function () {
                    $.LoadingOverlay("show");
                    self.getCountriesCall(self.getCountriesCallback);
                });

                $('#countries').on('change', function (e) {
                    self.temp_location['country'] = {
                        code :$('#countries').select2('data')[0].id,
                        name :$('#countries').select2('data')[0].text
                    }

                    if (self.temp_location.country.code == 'US') {
                        self.getStates();
                        self.temp_location.state = {
                            abbr: 'any',
                            name: ''
                        };
                    }else{
                        self.temp_location.state = {
                            abbr: '',
                            name: ''
                        };
                    }
                    
                    self.temp_location.city = {
                        name: ''
                    };
                });

                $('#states').on('change', function (e) {
                    self.temp_location['state'] = {
                        abbr :$('#states').select2('data')[0].id,
                        name :$('#states').select2('data')[0].text
                    }
                    
                    self.temp_location.city = {
                        name: ''
                    };

                });

            });
        }
    }
</script>
<style>
    form .form-section {
        border-bottom: 1px solid #eceff2;
    }

    .dz-image>img {
        width: 120px !important;
        height: auto !important;
    }

    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .error-dropzone {
        border: 2px dashed #b60707 !important;
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }

    .swal-text {
        text-align: center !important;
    }

    .dropdown-item:focus,
    .dropdown-item:hover {
        background-color: #fff;
        color: #000;
    }
</style>