<template>
    <div class="tt-layout tt-sticky-block__parent ">
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-page__breadcrumbs">
                    <ul class="tt-breadcrumbs">
                        <li>
                            <a href="/">
                                <i class="icon-home"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/cart">Cart</a>
                        </li>
                        <li>
                            <span>Checkout</span>
                        </li>
                    </ul>
                </div>
                <div class="tt-checkout">
                    <div class="row">
                        <div class="col-md-6 offset-md-1 col-xl-4 offset-xl-2">
                            <div style="padding-left: 30px">
                                <h4>Checkout</h4>
                            </div>
                            <div class="tt-summary--border" style="padding-left: 30px">
                                <h5>Order Summary</h5>
                            </div>
                            <div class="tt-summary__products tt-summary--border" style="margin-bottom: 10px; padding-bottom: 10px">
                                <div class="row"  style="padding-left:30px; padding-right: 30px">
                                    <ul>
                                        <li v-for="(cart_item, index) in cart_items" :key="index">
                                            <div>
                                                <a :href="'/items/'+cart_item.sku">
                                                    <img :src="cart_item.thumb_path ? '/storage/' + cart_item.thumb_path : '/images/item-placeholder.jpg'" alt="Image name">
                                                </a>
                                            </div>
                                            <div>
                                                <p>
                                                    <a :href="'/items/'+cart_item.sku">
                                                    <p>{{cart_item.name}} <span v-if="cart_item.options && !Array.isArray(cart_item.options)"><br>
                                                        (<span v-for="(opt, index, i) in cart_item.options" :key="index" style="text-transform:capitalize;font-size:12px;">
                                                            {{paintOptions(index, opt, i)}}
                                                        </span>)
                                                    </span></p></a>
                                                </p>
                                                <span class="tt-summary__products_price">
                                                    <span class="tt-summary__products_price-count">{{cart_item.qty}}</span>
                                                    <span>x</span>
                                                    <span class="tt-summary__products_price-val">
                                                        <span class="tt-price">
                                                            <span v-if="cart_item.discount_percent">
                                                                ${{discounted_price(cart_item.price, cart_item.discount_percent)}}
                                                            </span>
                                                            <span v-else>
                                                                ${{parseFloat(cart_item.price).toFixed(2)}}
                                                            </span>
                                                        </span>
                                                    </span><br>
                                                    <span class="tt-summary__products_price-val">
                                                        <a @click="removeItem(cart_item.id)" href="#" style="font-size:11px;"> Delete</a>
                                                    </span>
                                                </span>
                                                <!-- <div class="tt-summary__products_param-control tt-summary__products_param-control--open">
                                                    <span>Details</span>
                                                    <i class="icon-down-open"></i>
                                                </div>
                                                <div class="tt-summary__products_param">
                                                    <span class="tt-summary__products_color">Description:
                                                        <span>{{cart_item.name}}</span>
                                                    </span>
                                                </div> -->
                                            </div>
                                            <div class="tt-counter tt-counter__inner" data-min="1" data-max="999">
                                                <input :id="cart_item.id" v-model="cart_item.qty" type="text" :data-item_id="cart_item.id" @keyup.enter="changeQty(cart_item.id, cart_item.qty, cart_item.total_qty)"
                                                    @focusout="changeQty(cart_item.id, cart_item.qty, cart_item.total_qty)" class="form-control" style="padding: 12px 20px 12px 0;">
                                                <div class="tt-counter__control">
                                                    <span @click="increase(cart_item.id, cart_item.qty, cart_item.total_qty)" class="icon-up-circle" :class="{'disable_span' : error.max_reached, 'enable_span' : !error.max_reached}"
                                                        data-direction="next"></span>
                                                    <span @click="decrease(cart_item.id, cart_item.qty, cart_item.total_qty)" class="icon-down-circle" data-direction="prev"></span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="row" style="padding-left:30px; padding-right: 30px">
                                    <div class="col-md-12">
                                        <h5 class="ttg-mt--50 ttg-mb--20">
                                            <span v-if="!current_account">Contact info &</span>
                                            <span>Shipping address</span>
                                        </h5>
                                        <div class="tt-checkout__form">
                                            <div class="row">
                                                <template v-if="!current_account">
                                                    <div class="col-md-12" style="padding-left:0;padding-right:0;">
                                                        <label>Name:</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <input v-model="customer_name" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.customer_name, 'input-bordered' : !error.customer_name}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.customer_name" class="input-error-msg">{{error_message.customer_name}}</div>
                                                    </div>
                                                    <div class="col-md-12" style="padding-left:0;padding-right:0;">
                                                        <label>Email:</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <input v-model="customer_email" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.customer_email, 'input-bordered' : !error.customer_email}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.customer_email" class="input-error-msg">{{error_message.customer_email}}</div>
                                                    </div>
                                                </template>
                                                <template v-show="current_account && current_account.addresses.length > 0 && !showAddAddress">
                                                    <div v-for="(address, index) in current_account.addresses" class="col-md-12" :key="index">
                                                        <div class="skin skin-square">
                                                            <div class="form-group text-left">
                                                                <fieldset>
                                                                    <input type="radio" :id="'radio_address' + address.id" :value="address.id" class="shipping_address" name="shipping_address" >
                                                                    <label :for="'radio_address' + address.id">
                                                                        {{address.address1}} {{address.address2}} {{address.city}} {{address.zip}} {{address.state}} {{address.country}}
                                                                    </label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="padding: 20px">
                                                        <div v-if="error.shipping_country && shipping_address.shipping_country" class="input-error-msg">{{error_message.shipping_country}}</div>
                                                        <div v-if="error.shipping_zip && shipping_address.shipping_zip" class="input-error-msg">{{error_message.shipping_zip}}</div>
                                                        <div v-if="error.shipping_state && shipping_address.shipping_state" class="input-error-msg">{{error_message.shipping_state}}</div>
                                                    </div>

                                                </template>
                                                <div v-show="!showAddAddress" class="row">
                                                    <div class="col-md-12">
                                                        <div v-if="error.shipping_address" class="input-error-msg" style="float: right !important">{{error_message.shipping_address}}</div>
                                                        <div>
                                                            <a @click="showNewAddress()" class="btn" style="cursor: pointer">Add shipping address</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-show="showAddAddress" class="row">
                                                    <div class="col-md-12">
                                                        <label>Country:</label>
                                                        <div class="tt-input">
                                                            <!-- <input v-model="shipping_address.shipping_country" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.shipping_country, 'input-bordered' : !error.shipping_country}"> -->
                                                            <select id="select2-countries" v-model="shipping_address.shipping_country" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%"
                                                                data-placeholder="Select country...">
                                                                <option :value="-1"></option>
                                                                <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                                            </select>
                                                        </div>
                                                        <div v-if="error.shipping_country" class="input-error-msg">{{error_message.shipping_country}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Address</label>
                                                        <div class="tt-input">
                                                            <input name="billing_address" v-model="shipping_address.shipping_address" type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.shipping_address, 'input-bordered' : !error.shipping_address}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.shipping_address" class="input-error-msg">{{error_message.shipping_address}}</div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>City:</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <input name="billing_city" v-model="shipping_address.shipping_city" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.shipping_city, 'input-bordered' : !error.shipping_city}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.shipping_city" class="input-error-msg">{{error_message.shipping_city}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>State/Province:</label>
                                                        <div class="tt-input">
                                                            <div v-show="shipping_address.shipping_country == 'US'">
                                                                <select id="select2-states" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%"
                                                                    data-placeholder="Select state...">
                                                                    <option :value="-1"></option>
                                                                    <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                                                </select>
                                                            </div>
                                                            <input v-show="shipping_address.shipping_country != 'US'" v-model="shipping_address.shipping_state" type="text" class="form-control colorize-theme6-bg input-bordered"
                                                                :class="{'input-bordered-error' : error.shipping_state, 'input-bordered' : !error.shipping_state}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.shipping_state" class="input-error-msg">{{error_message.shipping_state}}</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Zip/Postal Code:</label>
                                                        <div class="tt-input">
                                                            <input name="billing_zip" v-model="shipping_address.shipping_zip" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.shipping_zip, 'input-bordered' : !error.shipping_zip}" autocomplete="off">
                                                        </div>
                                                        <div v-if="error.shipping_zip" class="input-error-msg">{{error_message.shipping_zip}}</div>
                                                    </div>

                                                    <!--  <template v-if="customer_payment_methods.length > 0"> -->
                                                    <div class="col-md-4">
                                                        <div v-if="current_account" class="skin skin-square">
                                                            <div class="form-group text-left">
                                                                <fieldset>
                                                                    <input type="checkbox" id="saveShipping">
                                                                    <label for="saveShipping">
                                                                        Save
                                                                    </label>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="shipping_integration" class="col-md-8">
                                                            <div class="form-group text-right">
                                                                <span style="padding: 10px">
                                                                    <b>
                                                                        <a style="cursor: pointer" @click="setShippingAddress()">View shipping options</a>
                                                                    </b>
                                                                </span>
                                                                <span v-if="current_account" style="padding: 10px">
                                                                    <b>
                                                                        <a style="cursor: pointer" @click="showCurrentAddress()">Cancel</a>
                                                                    </b>
                                                                </span>
                                                            </div>
                                                    </div>
                                                    <!-- </template> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top:15px;">
                                        <div class="skin skin-square">
                                            <div class="form-group text-left">
                                                <fieldset>
                                                    <input type="checkbox" id="shippingAsBilling">
                                                    <label for="shippingAsBilling">
                                                        Billing address is the same that shipping address.
                                                    </label>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <template v-if="billingAddress == false">
                                        <div class="col-md-12">
                                            <h5 class="ttg-mt--10">
                                                <span>Billing address</span>
                                            </h5>
                                        </div>
                                            <div class="col-md-12">
                                            <label>Country:</label>
                                            <div class="tt-input">
                                                <input v-model="billing_address.billing_country" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.billing_country, 'input-bordered' : !error.billing_country}">
                                            </div>
                                            <div v-if="error.billing_country" class="input-error-msg">{{error_message.billing_country}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Address</label>
                                            <div class="tt-input">
                                                <input name="billing_address" v-model="billing_address.billing_address" type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.billing_address, 'input-bordered' : !error.billing_address}">
                                            </div>
                                            <div v-if="error.billing_address" class="input-error-msg">{{error_message.billing_address}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>City:</label>
                                            <div class="tt-input tt-input-valid--false">
                                                <input name="billing_city" v-model="billing_address.billing_city" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.billing_city, 'input-bordered' : !error.billing_city}">
                                            </div>
                                            <div v-if="error.billing_city" class="input-error-msg">{{error_message.billing_city}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Zip/Postal Code:</label>
                                            <div class="tt-input">
                                                <input name="billing_zip" v-model="billing_address.billing_zip" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.billing_zip, 'input-bordered' : !error.billing_zip}">
                                            </div>
                                            <div v-if="error.billing_zip" class="input-error-msg">{{error_message.billing_zip}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>State/Province:</label>
                                            <div class="tt-input">
                                                <input v-model="billing_address.billing_state" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.billing_state, 'input-bordered' : !error.billing_state}">
                                            </div>
                                            <div v-if="error.billing_state" class="input-error-msg">{{error_message.billing_state}}</div>
                                        </div>
                                    </template>
                                </div>
                                <div v-if="Object.keys(current_shipping_methods).length > 0" class="row"  style="padding-left:30px; padding-right: 30px">
                                    <div class="col-md-12">
                                        <h5 style="margin-top: 25px">
                                            <span>Shipping methods</span>
                                        </h5>
                                        <div class="tt-checkout__form">
                                            <div class="row">
                                                <div v-for="(shipping_method, index) in codes" class="col-md-12" :key="index" v-if="typeof current_shipping_methods[shipping_method] != 'undefined'">
                                                    <div class="skin skin-square">
                                                        <div class="form-group text-left">
                                                            <fieldset>
                                                                <input type="radio" :id="'radio_shipping' + current_shipping_methods[shipping_method].code" :value="current_shipping_methods[shipping_method].code"
                                                                    class="shipping_methods" name="shipping_methods">
                                                                <label :for="'radio_shipping' + current_shipping_methods[shipping_method].code">
                                                                {{current_shipping_methods[shipping_method].description}} -
                                                                <span style="color: gray; font-size: 14px">
                                                                    <span v-if="current_shipping_methods[shipping_method].code == '03'">
                                                                        <b><span class="cross-out">${{current_shipping_methods[shipping_method].cost}}</span> (Free)</b>
                                                                    </span>
                                                                    <span v-else>
                                                                        <b>${{current_shipping_methods[shipping_method].cost}}</b>
                                                                    </span>
                                                                </span>
                                                                </label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-show="empty_msg" class="row">
                                    <div class="col-md-12">
                                        There is not shipping information available at this time. We will notify you about shipping details once your order has been
                                        shipped.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-4">
                            <div class="tt-summary" style="margin-top: 20px">
                                <div v-if="show_discount" class="tt-summary--border row" style="margin-bottom:20px; padding-bottom:0px;">
                                    <div class="col-xs-9" style="margin-top:20px; margin-bottom:20px;">
                                        <div class="tt-input">
                                            <!-- <span>Have a Coupon?</span> -->
                                            <input v-model="discount_code" type="text" class="form-control input-bordered" placeholder="COUPON" 
                                            :class="{'input-bordered-error' : error.coupon, 'input-bordered' : !error.coupon}">
                                        </div>
                                        <span v-if="error.coupon" class="input-error-msg">{{error_message.coupon}}</span>
                                    </div>
                                    <div class="col-xs-2" style="align-self:center;">
                                        <a href="#" @click="checkDiscount()" class="tt-checkout__btn-order btn btn-type--icon colorize-btn6" :disabled="enabled_apply">Apply</a>
                                    </div>
                                    <div v-if="order.discount_amount > 0" class="col-xs-12">
                                        <div  style="font-size:12px; color: black; font-weight: bold;padding-bottom:10px;">
                                            <p>Coupon: {{order.discount_code}}</p>
                                            <p>{{coupon_message}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <p style="font-size:12px; color:red;margin-bottom:10px;font-weight:bold;">A shipping address is needed before entering a coupon.</p>
                                </div>
                                <div class="tt-summary--border row">
                                    <div class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total" >
                                            <p style="font-size: 18px">Items Subtotal:</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">${{Helpers.numberFormat((order.subtotal),2)}}</span></p>
                                        </div>
                                    </div>
                                    <div v-if="order.discount_amount > 0" class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total">
                                            <p style="font-size: 18px">Discount ({{order.discount_code}}):</p>
                                        </div>
                                    </div>
                                    <div  v-if="order.discount_amount > 0" class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">- ${{Helpers.numberFormat(order.discount_amount*1,2)}}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total">
                                            <p style="font-size: 18px">Shipping & Handling: </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">${{Helpers.numberFormat(order.shipping_cost_estimated*1,2)}}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total">
                                            <p style="font-size: 18px">Total Before Tax: </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">${{Helpers.numberFormat(order.subtotal*1 - order.discount_amount*1 + order.shipping_cost_estimated*1,2)}}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total">
                                            <p style="font-size: 18px">Taxes:</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">${{Helpers.numberFormat(order.total_tax_amount*1,2)}}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-7 col-xs-6">
                                        <div class="tt-summary__total">
                                            <p style="font-size: 18px">Order Total: </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-xs-6 text-right">
                                        <div class="tt-summary__total">
                                            <p><span style="color: dimgray; font-size: 18px">${{Helpers.numberFormat(order.subtotal*1 - order.discount_amount*1 + order.shipping_cost_estimated*1+order.total_tax_amount*1,2)}}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tt-summary--border">
                                    <div class="col-md-12" style="padding:0;">
                                        <template v-if="customer_payment_methods.length > 0">
                                            <h5 class="ttg-mt--60 ttg-mb--30" style="margin-top: 20px !important">Pay with your saved cards</h5>
                                            <div class="tt-checkout__payment">
                                                <ul>
                                                    <li v-for="(payment_method, index) in customer_payment_methods" :key="index">
                                                        <label :for="'radio_payment' + payment_method.id" class="tt-checkbox-circle">
                                                            <input :id="'radio_payment' + payment_method.id" :value="payment_method.id" v-model="selected_customer_method_id" type="radio">
                                                            <span :class="{'input-bordered-error' : error.payment_method, 'radio-pay-ch' :  selected_customer_method_id, 'radio-pay-uch' : !selected_customer_method_id }"></span>
                                                        </label>
                                                        <span style="margin-right: 10px">
                                                            <img v-if="payment_method.brand == 'MasterCard'" src="/images/creditCard/mastercard.png" style="width: 50px; height: auto;">
                                                            <img v-else-if="payment_method.brand == 'AMEX'" src="/images/creditCard/amex.png" style="width: 50px; height: auto;">
                                                            <img v-else-if="payment_method.brand == 'Discover'" src="/images/creditCard/discover.png" style="width: 50px; height: auto;">
                                                            <img v-else-if="payment_method.brand == 'Visa'" src="/images/creditCard/visa.png" style="width: 50px; height: auto;">
                                                            <img v-else-if="payment_method.brand == 'American Express'" src="/images/creditCard/amex.png" style="width: 50px; height: auto;">
                                                        </span>
                                                        <span>
                                                            <span>{{payment_method.brand}} ending in</span>
                                                            <span>
                                                                <b>{{payment_method.last_numbers}}</b>
                                                            </span>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                        </template>
                                        <h5 class="ttg-mt--60 ttg-mb--30" style="margin-top: 20px !important">Pay with</h5>
                                        <div class="tt-checkout__payment">
                                            <ul>
                                                <li v-for="(payment_method, index) in payment_methods" v-if="(payment_method.name == 'paypal' && paypal) || payment_method.name=='credit-card'"
                                                    :key="index">
                                                    <label :for="'radio_payment' + payment_method.name" class="tt-checkbox-circle">
                                                        <input :id="'radio_payment' + payment_method.name" :value="payment_method.id" v-model="selected_payment_method_id" type="radio">
                                                        <span :class="{'input-bordered-error' : error.payment_method, 'radio-pay-ch' :  selected_payment_method_id, 'radio-pay-uch' : !selected_payment_method_id}"></span>
                                                    </label>
                                                    <span :class="{'fa fa-paypal' : payment_method.name == 'paypal', 'fa fa-credit-card' : payment_method.name == 'credit-card'}"
                                                        style="font-size: 18px"></span>
                                                    <span style="padding-left: 15px">{{ payment_method.label}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-if="error.payment_method" class="input-error-msg">{{error_message.payment_method}}</div>
                                        <!-- <div v-show="selected_payment_method_name == 'paypal'" style="margin-top: 15px">
                                                <div class="tt-checkout__form">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Paypal email:</label>
                                                            <div class="tt-input">
                                                                <input type="text" v-model="paypalEmail" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.paypalEmail, 'input-bordered' : !error.paypalEmail}">
                                                            </div>
                                                            <div v-if="error.paypalEmail" class="input-error-msg">{{error_message.paypalEmail}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        <div v-show="selected_payment_method_name == 'credit-card'" style="margin-top: 15px">
                                            <div class="tt-checkout__form">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Card holder</label>
                                                        <div class="tt-input">
                                                            <input v-model="cardInfo.card_holder" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.card_holder, 'input-bordered' : !error.card_holder}">
                                                        </div>
                                                        <div v-if="error.card_holder" class="input-error-msg">{{error_message.card_holder}}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Card number</label>
                                                        <div class="tt-input tt-input-valid--true">
                                                            <input id="cc_number" v-model="cardInfo.card_number" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.card_number || !valid_card, 'input-bordered' : !error.card_number || valid_card}">
                                                        </div>
                                                        <img v-if="card_brand" :src="card_brand" style="float: right; width: 70px ;height: auto">
                                                        <div v-if="error.card_number" class="input-error-msg">{{error_message.card_number}}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3 col-lg-4" style="padding-right:5px;">
                                                        <label>Month</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <select id="select2-months" v-model="cardInfo.exp_month" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%">
                                                                <option value="" disabled selected>Month</option>
                                                                <option v-for="(item, key, index) in 12" :key="index">
                                                                    <span v-if="item >= 1 && item <= 9">0{{item}}</span>
                                                                    <span v-else>{{item}}</span>
                                                                </option>
                                                            </select>
                                                            <!-- <input v-model="cardInfo.exp_date" type="text" placeholder="MM/YYYY" class="form-control colorize-theme6-bg input-bordered"
                                                                :class="{'input-bordered-error' : error.exp_date, 'input-bordered' : !error.exp_date}" style="padding:12px 5px;"> -->
                                                        </div>
                                                        <div v-if="error.exp_month" class="input-error-msg">{{error_message.exp_month}}</div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 col-lg-4" style="padding-left:5px;padding-right:5px;">
                                                        <label>Year</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <select id="select2-years" v-model="cardInfo.exp_year"  class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%" >
                                                                <option value="" disabled selected>Year</option>
                                                                <option style="padding: 10px" v-for="(item, key, index) in years" :key="index">{{item}}</option>
                                                            </select>
                                                            <!-- <input v-model="cardInfo.exp_date" type="text" placeholder="MM/YYYY" class="form-control colorize-theme6-bg input-bordered"
                                                                :class="{'input-bordered-error' : error.exp_date, 'input-bordered' : !error.exp_date}" style="padding:12px 5px;"> -->
                                                        </div>
                                                        <div v-if="error.exp_year" class="input-error-msg">{{error_message.exp_year}}</div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-5 col-lg-4">
                                                        <label>CVV</label>
                                                        <div class="tt-input tt-input-valid--false">
                                                            <input v-model="cardInfo.cvv" type="text" class="form-control colorize-theme6-bg input-bordered" :class="{'input-bordered-error' : error.cvv, 'input-bordered' : !error.cvv}"
                                                                style="padding:12px 5px;">
                                                        </div>
                                                        <div v-if="error.cvv" class="input-error-msg">{{error_message.cvv}}</div>
                                                    </div>
                                                </div>
                                                <div v-show="current_account" class="row">
                                                    <div class="col-md-12">
                                                        <div class="tt-checkout__payment">
                                                            <ul>
                                                                <li>
                                                                    <label for="save_card" class="tt-checkbox-circle">
                                                                        <input id="save_card" v-model="save_card" type="checkbox">
                                                                        <span :class="{'radio-pay-ch' :  save_card, 'radio-pay-uch' : !save_card }"></span>
                                                                    </label>
                                                                    <span>
                                                                        <span>Save this card for future use</span>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tt-summary--border">
                                    <a id="place-order" @click="placeOrder()" style="cursor: pointer" class="tt-checkout__btn-order btn btn-type--icon colorize-btn6">
                                        <i class="icon-check"></i>
                                        <span>Place Order</span>
                                    </a>
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
    import CartServices from '../services/CartServices.js'
    import AccountServices from '../services/AccountServices.js'
    import Helpers from '../../../../../misc/helpers.js';
    import misc from '../../../../../misc/payments';

    export default {
        mixins: [CartServices, AccountServices, Helpers],
        props: ['items', 'account', 'payment_methods', 'client_key', 'torder', 'discount', 'codes',
                'api_id', 'card_processor', 'paypal', 'available_shipping_methods', 'shipping_integration'],
        data() {
            return {
                Helpers: Helpers,
                cart_items: [],
                subtotal: 0.00,
                total: 0.00,
                total_taxes: 0.00,
                shipping_fee: 0.00,
                current_discount: {},
                selected_payment_method_id: '',
                selected_customer_method_id: '',
                selected_payment_method_name: '',
                selected_shipping_method_code: '03',
                current_shipping_methods: [],
                payment_type: '',
                paypalEmail: '',
                customer_payment_methods: [],
                valid_card: false,
                card_brand: '',
                showAddAddress: false,
                saveAddress: false,
                current_account: '',
                save_card: false,
                cardInfo: {
                    card_holder: '',
                    card_number: '',
                    exp_date: '',
                    exp_month: '',
                    exp_year: '',
                    cvv: ''
                },
                countries: [],
                states: [],
                customer_name: '',
                customer_email: '',
                shipping_address: {
                    address_id:0,
                    shipping_address: '',
                    shipping_address_cont: '',
                    shipping_city: '',
                    shipping_state: '',
                    shipping_zip: '',
                    shipping_country: '',
                },
                billing_address: {
                    billing_address: '',
                    billing_address_cont: '',
                    billing_city: '',
                    billing_state: '',
                    billing_zip: '',
                    billing_country: '',
                },
                discount_code: '',
                error: {
                    shipping_address: false,
                    shipping_city: false,
                    shipping_zip: false,
                    shipping_state: false,
                    shipping_country: false,

                    billing_address: false,
                    billing_city: false,
                    billing_zip: false,
                    billing_state: false,
                    billing_country: false,

                    card_holder: false,
                    card_number: false,
                    exp_date: false,
                    exp_month: false,
                    exp_year: false,
                    cvv: false,

                    paypalEmail: false,
                    coupon: false,

                    payment_method: false,
                    customer_name: false,
                    customer_email: false,
                    max_reached: false


                },
                error_message: {
                    shipping: '',
                    shipping_address: '',
                    shipping_city: '',
                    shipping_zip: '',
                    shipping_state: '',
                    shipping_country: '',

                    billing: '',
                    billing_address: '',
                    billing_city: '',
                    billing_zip: '',
                    billing_state: '',
                    billing_country: '',

                    card_holder: '',
                    card_number: '',
                    exp_date: '',
                    cvv: '',
                    exp_month: '',
                    exp_year: '',

                    paypalEmail: '',
                    coupon: '',

                    payment_method: '',
                    customer_name: '',
                    customer_email: '',
                    max_reached: ''
                },
                payment: payments.driver(this.card_processor),
                order: {
                    id:0,
                    total_amount:0.00,
                    shipping_cost_estimated:0.00,
                    total_tax_amount:0.00,
                    discount_amount:0.00,
                    subtotal:0.00
                },
                send_order: {
                },
                billingAddress: true,
                showBillingAddress: false,
                carrier: 'ups',
                empty_msg: '',
                show_discount: false,
                coupon_message: '',
                enabled_apply: true,
                years:[],
            }
        },
        methods: {
            paintOptions(index, value, i){
                var string = '';
                if(i>0){
                    string+=', '
                }
                string += index+':'+value;
                return string;
            },
            placeOrder() {
                if (this.validateShippingInput(false) && this.validateBillingInput()) {
                    if (this.selected_payment_method_id > 0 || this.selected_customer_method_id > 0) {
                        if (this.selected_payment_method_name == 'paypal') {
                            // if (this.validatePaypalEmail()) {
                            this.submitOrder();
                            // }
                        }
                        else if (this.selected_payment_method_name == 'credit-card') {
                            if (this.validateCardInfo()) {
                                if (this.valid_card) {
                                    this.submitOrder();
                                }
                            }
                        }
                        else if (this.selected_payment_method_name == 'profile') {
                            this.submitOrder();
                        }
                    } else {
                        this.error.payment_method = true;
                        this.error_message.payment_method = 'Select a payment method';
                    }
                }
            },
            submitOrder() {
                this.send_order = {
                    id: this.order.id,
                    // total_amount: parseFloat(this.total).toFixed(2),
                    total_amount: this.order.total_amount,
                    address_id: this.shipping_address.address_id,
                    shipping_address_1: this.shipping_address.shipping_address,
                    shipping_address_2: this.shipping_address.shipping_address_cont,
                    shipping_city: this.shipping_address.shipping_city,
                    shipping_zip: this.shipping_address.shipping_zip,
                    shipping_state: this.shipping_address.shipping_state,
                    shipping_country: this.shipping_address.shipping_country,
                    carrier: this.carrier,
                    payment_type: this.selected_payment_method_name,
                    order_items: this.cart_items,
                    save_address: this.saveAddress,
                    save_card: this.save_card
                }
                if (this.billingAddress) {
                    this.send_order.billing_address = this.shipping_address.shipping_address;
                    this.send_order.billing_city = this.shipping_address.shipping_city;
                    this.send_order.billing_zip = this.shipping_address.shipping_zip;
                    this.send_order.billing_state = this.shipping_address.shipping_state;
                    this.send_order.billing_country = this.shipping_address.shipping_country;
                } else {
                    this.send_order.billing_address = this.billing_address.billing_address;
                    this.send_order.billing_city = this.billing_address.billing_city;
                    this.send_order.billing_zip = this.billing_address.billing_zip;
                    this.send_order.billing_state = this.billing_address.billing_state;
                    this.send_order.billing_country = this.billing_address.billing_country;
                }
                if (this.selected_payment_method_id > 0) {
                    this.send_order.payment_method_id = this.selected_payment_method_id;
                }
                if (this.selected_shipping_method_code != "") {
                    this.send_order.selected_shipping_method_code = this.selected_shipping_method_code;
                }
                if (this.send_order.discount_code != "") {
                    this.send_order.discount_code = this.order.discount_code;
                }
                if (!this.selected_customer_method_id) {
                    if (!this.current_account) {
                        this.send_order.customer_name = this.customer_name;
                        this.send_order.customer_email = this.customer_email;
                    }
                    if (this.selected_payment_method_name == 'paypal') {
                        $.LoadingOverlay("show");
                        this.placeOrders(this.send_order, this.placeOrderCallback);
                        // this.order.paypalEmail = this.paypalEmail;
                    } else if (this.selected_payment_method_name == 'credit-card') {
                        $.LoadingOverlay("show");
                        var date = this.cardInfo.exp_date.split('/');
                        // this.cardInfo.exp_month = date[0];
                        // this.cardInfo.exp_year = date[1];
                        var paymentData = {
                            clientKey: this.client_key,
                            apiLoginID: this.api_id,
                            cardInfo: this.cardInfo,
                            type: 'card'
                        };
                        this.send_order.card_exp_date = this.cardInfo.exp_month+'/'+this.cardInfo.exp_year;
                        this.payment.card.createCardToken(paymentData, this.createCardTokenCallback);

                        // order.card_holder = this.cardInfo.card_holder;
                        // order.card_number = this.cardInfo.card_number;
                        // order.card_exp_date = this.cardInfo.exp_date;
                        // order.card_cvv = this.cardInfo.cvv;
                        // order.customer_type = 'individual';
                    }
                } else {
                    this.send_order.selected_customer_method_id = this.selected_customer_method_id;
                    this.send_order.type = 'card';
                    this.send_order.last_4 = this.cardInfo.card_number.substring(this.cardInfo.card_number.length - 4)
                    this.send_order.save_card = false;
                    $.LoadingOverlay("show");
                    this.placeOrders(this.send_order, this.placeOrderCallback);
                }

                // if (!this.current_account) {
                //     order.customer_name = this.customer_name;
                //     order.customer_email = this.customer_email;
                // }
                // if (this.selected_payment_method_name == 'paypal') {
                //     order.paypalEmail = this.paypalEmail;
                // } else if (this.selected_payment_method_name == 'credit-card') {
                //     order.card_holder = this.cardInfo.card_holder;
                //     order.card_number = this.cardInfo.card_number;
                //     order.card_exp_date = this.cardInfo.exp_date;
                //     order.card_cvv = this.cardInfo.cvv;
                // }


            },
            createCardTokenCallback(response) {
                if (response.status > 0) {
                    this.send_order.card_holder = this.cardInfo.card_holder;
                    this.send_order.customer_type = 'individual';
                    this.send_order.token = response.data.token;
                    if (typeof response.data.description != 'undefined') {
                        this.send_order.description = response.data.description;
                    }
                    this.send_order.type = 'card';
                    this.send_order.last_4 = this.cardInfo.card_number.substring(this.cardInfo.card_number.length - 4)
                    this.placeOrders(this.send_order, this.placeOrderCallback);

                } else {
                    $.LoadingOverlay("hide");
                    var errors = '';
                    for (var i in response.errors) {
                        errors += response.errors[i].text + '.';
                    }
                    swal('Error!', errors, 'error');
                }
            },

            placeOrderCallback(response) {
                if (response.status == 1) {
                    if (this.selected_payment_method_name != 'paypal') {
                        this.cart_items = [];
                        window.location.href = '/orders/order-confirmation/' + response.data.id + '/1';
                    } else {
                        var form = $('<form />', {
                            action: response.data['info']['url'],
                            method: 'POST',
                            style: 'display: none;'
                        });
                        form.append(response.data['info']['pform']);
                        form.appendTo('body').submit();
                    }
                } else {
                    if (Helpers.isAssoc(response.errors)) {
                        for (var field in response.errors) {
                            this.error_message[field] = response.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        if (response.code == '003' || response.code == '001') {
                            swal('Error', response.errors[0], 'error');
                        }
                        else if (response.code == '021') {
                            this.invalidDiscount(response);
                        }
                        else if (response.code == '005') {
                            swal({
                                title: "Error",
                                text: response.errors[0],
                                icon: "error",
                                buttons: true,
                                buttons: {
                                    confirm: {
                                        text: "Ok",
                                        value: true,
                                        visible: true,
                                        className: "",
                                        closeModal: false
                                    }
                                }
                            }).then(isConfirm => {
                                if (isConfirm) {
                                    window.location.href = '/cart';
                                }
                            });
                            
                        }
                        else {
                            this.showErrorNotification(response.data.id);
                        }
                    }
                    $.LoadingOverlay('hide');
                    
                }
            },
            validateShippingInput(just_address) {
                if (this.shipping_address.shipping_address != '' && this.shipping_address.shipping_city != ''
                    && this.shipping_address.shipping_zip != '' && this.shipping_address.shipping_state != ''
                    && this.shipping_address.shipping_country != '') {
                    if (just_address == false) {
                        if (!this.current_account) {
                            if (this.customer_name != '' && this.customer_email != '') {
                                if (Helpers.validateEmail(this.customer_email)) {
                                    return true;
                                } else {
                                    this.error.customer_email = true;
                                    this.error_message.customer_email = 'Customer email is incorrect';
                                    return false;
                                }
                            } else {
                                if (this.customer_email == '') {
                                    this.error.customer_email = true;
                                    this.error_message.customer_email = 'Customer email is required';
                                }
                                if (this.customer_name == '') {
                                    this.error.customer_name = true;
                                    this.error_message.customer_name = 'Customer name is required';
                                }
                                return false;
                            }
                        }
                    }
                    return true;
                }
                else {
                    if (this.shipping_address.shipping_address == '') {
                        this.error.shipping_address = true;
                        this.error_message.shipping_address = 'Address is required';
                    }
                    if (this.shipping_address.shipping_city == '') {
                        this.error.shipping_city = true;
                        this.error_message.shipping_city = 'City is required';
                    }
                    if (this.shipping_address.shipping_zip == '') {
                        this.error.shipping_zip = true;
                        this.error_message.shipping_zip = 'Zip code is required';
                    }
                    if (this.shipping_address.shipping_state == '') {
                        if (this.shipping_address.shipping_country == 'US') {
                            $("#select2-states").select2({
                                containerCssClass: "select2-error",
                                matcher: this.matchCustom
                            });
                        } else {
                            this.error.shipping_state = true;
                        }
                        this.error_message.shipping_state = 'State/province is required';
                    }
                    if (this.shipping_address.shipping_country == '') {
                        $("#select2-countries").select2({
                            containerCssClass: "select2-error",
                            matcher: this.matchCustom
                        });
                        this.error.shipping_country = true;
                        this.error_message.shipping_country = 'Country is required';
                    }
                }
            },
            validateBillingInput() {
                if (this.billingAddress) {
                    return true;
                }

                if (this.billing_address.billing_address != '' && this.billing_address.billing_city != ''
                    && this.billing_address.billing_zip != '' && this.billing_address.billing_state != ''
                    && this.billing_address.billing_country != '') {
                    return true;
                }
                else {
                    if (this.billing_address.billing_address == '') {
                        this.error.billing_address = true;
                        this.error_message.billing_address = 'Billing address is required';
                    }
                    if (this.billing_address.billing_city == '') {
                        this.error.billing_city = true;
                        this.error_message.billing_city = 'Billing city is required';
                    }
                    if (this.billing_address.billing_zip == '') {
                        this.error.billing_zip = true;
                        this.error_message.billing_zip = 'Billing zip code is required';
                    }
                    if (this.billing_address.billing_state == '') {
                        this.error.billing_state = true;
                        this.error_message.billing_state = 'Billing state/province is required';
                    }
                    if (this.billing_address.billing_country == '') {
                        this.error.billing_country = true;
                        this.error_message.billing_country = 'Billing country is required';
                    }
                }
            },
            validateCardInfo() {
                if (this.cardInfo.card_holder != '' && this.cardInfo.card_number != ''
                    && this.cardInfo.exp_month != '' && this.cardInfo.exp_year != '' && this.cardInfo.cvv != '') {
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
                        this.error_message.exp_month = 'Required';
                        $("#select2-months").select2({
                            containerCssClass: "select2-error"
                        });
                    }
                    if (this.cardInfo.exp_year == '') {
                        this.error.exp_year = true;
                        this.error_message.exp_year = 'Required';
                        $("#select2-years").select2({
                            containerCssClass: "select2-error"
                        });
                    }
                    if (this.cardInfo.cvv == '') {
                        this.error.cvv = true;
                        this.error_message.cvv = 'Required';
                    }
                }
            },
            validatePaypalEmail() {
                if (this.paypalEmail != '') {
                    if (Helpers.validateEmail(this.paypalEmail)) {
                        return true;
                    } else {
                        this.error.paypalEmail = true;
                        this.error_message.paypalEmail = 'Email is incorrect';
                    }
                } else {
                    this.error.paypalEmail = true;
                    this.error_message.paypalEmail = 'Email is required';
                }
            },
            increase(item_id, qty, total_qty) {
                if (qty >= 1 && qty <= total_qty) {
                    $.LoadingOverlay("show");
                    this.error.coupon = false;
                    this.changeQtyCall(item_id, parseInt(qty) + 1, this.changeQtyHandler);
                } else {
                    this.error.max_reached = true;
                    this.error_message.max_reached = "Quantity must be less or equal than " + total_qty;
                }

            },
            decrease(item_id, qty, total_qty) {
                if (qty >= 1 && qty <= total_qty) {
                    $.LoadingOverlay("show");
                    this.error.coupon = false;
                    this.changeQtyCall(item_id, parseInt(qty) - 1, this.changeQtyHandler);
                } else {
                    this.error.max_reached = true;
                    this.error_message.max_reached = "Quantity must be less or equal than " + total_qty;
                }

            },
            changeQty(item_id, qty, total_qty) {
                if (qty >= 1 && qty <= total_qty) {
                    $.LoadingOverlay("show");
                    this.error.coupon = false;
                   
                    this.changeQtyCall(item_id, parseInt(qty), this.changeQtyHandler);
                } else {
                    this.removeItem(item_id);
                    this.error.max_reached = true;
                    this.error_message.max_reached = "Quantity must be less or equal than " + total_qty;
                }
            },
            changeQtyHandler(code, data, errors = []) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.error.max_reached = false;
                    this.cart_items = [];
                    this.fillCartItem(data.cart);
                    paintCart();
                    this.order.total_amount= parseFloat(data.order.total_amount);
                    this.order.subtotal= parseFloat(data.order.subtotal);
                    this.order.total_tax_amount= (data.order.total_tax_amount) ? parseFloat(data.order.total_tax_amount): 0.00;
                    this.order.shipping_cost_estimated= (data.order.shipping_cost_estimated) ? parseFloat(data.order.shipping_cost_estimated) : 0.00;
                    this.order.discount_amount= (data.order.discount_amount) ? parseFloat(data.order.discount_amount) : 0.00;
                    this.order.discount_code= (data.order.discount_code) ? data.order.discount_code : '';
                } else {
                    if(code == '021'){
                        this.error.max_reached = false;
                        this.cart_items = [];
                        this.fillCartItem(data.cart);
                        paintCart();
                        this.discount_code = this.order.discount_code;
                        this.order.total_amount= parseFloat(data.order.total_amount);
                         this.order.subtotal= parseFloat(data.order.subtotal);
                        this.order.total_tax_amount= (data.order.total_tax_amount) ? parseFloat(data.order.total_tax_amount): 0.00;
                        this.order.shipping_cost_estimated= (data.order.shipping_cost_estimated) ? parseFloat(data.order.shipping_cost_estimated) : 0.00;
                        this.order.discount_amount= (data.order.discount_amount && data.order.discount_amount > 0) ? parseFloat(data.order.discount_amount) : 0;
                        
                        this.order.discount_code = '';
                        this.error.coupon = true;
                        this.error_message.coupon = errors[0];
                    }else{
                        this.error.max_reached = true;
                    }
                }
            },
            // getSubTotal() {
            //     this.subtotal = 0.00;
            //     if (this.cart_items.length > 0) {
            //         for (var i in this.cart_items) {
            //             if (this.cart_items[i].discount_percent == null) {
            //                 this.subtotal = this.subtotal + (this.cart_items[i].price * this.cart_items[i].qty);
            //             } else {
            //                 this.subtotal = this.subtotal + ((parseFloat(this.cart_items[i].price).toFixed(2) - ((parseFloat(this.cart_items[i].price).toFixed(2) * this.cart_items[i].discount_percent) / 100)) * parseFloat(this.cart_items[i].qty).toFixed(2));
            //             }
            //         }
            //     }
            //     return this.subtotal;
            // },
            // getTotal() {
            //     this.total = 0.00;
                /* if (this.cart_items.length > 0) {
                    for (var i in this.cart_items) {
                        if (this.cart_items[i].discount_percent == null) {
                            this.total = this.total + ((this.cart_items[i].price * this.cart_items[i].qty) + this.shipping_fee);
                        } else {
                            this.total = this.total + (((parseFloat(this.cart_items[i].price).toFixed(2) - ((parseFloat(this.cart_items[i].price).toFixed(2) * this.cart_items[i].discount_percent) / 100)) * parseFloat(this.cart_items[i].qty).toFixed(2)) + this.shipping_fee);
                        }
                    }
                } */
            //     this.total = ((this.getSubTotal() + this.getTotalTaxes() + (this.shipping_fee * 1)) * 1 - this.discount*1).toFixed(2);

            //     return this.total;
            // },
            // getTotalTaxes() {
            //     this.total_taxes = 0.00;
            //     if (this.cart_items.length > 0) {
            //         for (var i in this.cart_items) {
            //             if (this.cart_items[i].taxable) {
            //                 if (this.cart_items[i].tax_percent > 0) {
            //                     this.total_taxes = this.total_taxes + ((parseFloat(this.cart_items[i].price).toFixed(2) * (this.cart_items[i].tax_percent / 100)) * this.cart_items[i].qty);
            //                 }
            //             }
            //         }
            //     }

            //     return this.total_taxes;
            // },
            discounted_product_total(price, discount_percent, qty) {
                var discounted_price = price - ((price * discount_percent) / 100);
                return parseFloat(discounted_price * qty).toFixed(2);
            },
            discounted_price(price, discount_percent) {
                var discounted_price = price - ((price * discount_percent) / 100);
                return parseFloat(discounted_price).toFixed(2);
            },
            product_total(price, qty) {
                var price = parseFloat(price).toFixed(2);
                var result = price * qty;
                return parseFloat(result).toFixed(2);
            },
            fillCartItem(data) {
                for (var property in data) {
                    if (data.hasOwnProperty(property)) {
                        this.cart_items.push(data[property]);
                    }
                }
                // this.getSubTotal();
                // this.getTotal();
                // this.getTotalTaxes();
            },
            showErrorNotification(order_id) {
                // window.location.href = '/orders/order-confirmation/' + order_id + '/0';
            },
            fillShippingAddress(id) {
                if (this.current_account) {
                    if (this.current_account.addresses.length > 0) {
                        for (var i in this.current_account.addresses) {
                            if (this.current_account.addresses[i].id == id) {
                                this.shipping_address.address_id = this.current_account.addresses[i].id;
                                this.shipping_address.shipping_address = this.current_account.addresses[i].address1;
                                this.shipping_address.shipping_address_cont = this.current_account.addresses[i].address2;
                                this.shipping_address.shipping_city = this.current_account.addresses[i].city;
                                this.shipping_address.shipping_state = this.current_account.addresses[i].state;
                                this.shipping_address.shipping_zip = this.current_account.addresses[i].zip;
                                this.shipping_address.shipping_country = this.current_account.addresses[i].country;
                            }
                        }
                    }
                }
                //console.log(this.shipping_address);
            },
            showNewAddress() {
                $.LoadingOverlay("show");
                this.showAddAddress = true;
                this.current_shipping_methods = Object.assign({});
                this.getCountriesCall(this.getCountriesCallback);
            },
            round(value, decimals) {
                return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
            },
            showCurrentAddress() {
                this.resetAddress();
                this.resetAddressErrors();
                // this.current_shipping_methods = Object.assign({});
                this.getShippingMethodsOnAddressChange();
                this.empty_msg = false;
                this.showAddAddress = false;
                this.checkDiscount();
            },
            resetAddress() {
                this.shipping_address.shipping_address = '';
                this.shipping_address.shipping_city = '';
                this.shipping_address.shipping_zip = '';
                this.shipping_address.shipping_state = '';
                this.shipping_address.shipping_country = 'US';
                this.showDiscount();
                this.getStatesCall(this.getStatesCallback);

            },
            resetAddressErrors() {
                this.error.shipping_address = false;
                this.error.shipping_address_cont = false;
                this.error.shipping_zip = false;
                this.error.shipping_city = false;
                this.error.shipping_state = false;
                this.error.shipping_country = false;
                this.error_message.shipping_address = '';
                this.error_message.shipping_address_cont = '';
                this.error_message.shipping_zip = '';
                this.error_message.shipping_city = '';
                this.error_message.shipping_state = '';
                this.error_message.shipping_country = '';
                $("#select2-countries").select2({
                    containerCssClass: "input-bordered",
                    matcher: this.matchCustom
                });
            },
            cancelAddress() {
                this.resetAddress();
                this.resetAddressErrors();
                this.showAddAddress = false;

            },
            ifCheckCallback(val) {
                let fee = 0;
                for (var i in this.current_shipping_methods) {
                    if (this.current_shipping_methods[i].code == val) {
                        if (this.current_shipping_methods[i].code == '03') {
                            fee = 0.00;
                        } else {
                            fee = (this.current_shipping_methods[i].cost * 1);
                        }
                        break;
                    }
                }
                return fee;
            },
            setShippingAddress() {
                if (this.validateShippingInput(true)) {
                    $.LoadingOverlay("show");
                    if (this.carrier == 'ups') {
                        this.current_shipping_methods = Object.assign({});
                        var params = this.shipping_address;
                        params.order_id = this.order.id;
                        this.getUpsShippingRatesCall(params, this.getUpsShippingRatesCallback);
                    }
                }
            },
            getShippingMethodsOnAddressChange() {
                $.LoadingOverlay("show");
                if (this.carrier == 'ups') {
                    this.current_shipping_methods = Object.assign({});
                    var params = this.shipping_address;
                    params.order_id = this.order.id;
                    this.getUpsShippingRatesCall(params, this.getUpsShippingRatesCallback);
                }
            },
            getTaxes() {
                this.current_shipping_methods = Object.assign({});
                var params = this.shipping_address;
                params.order_id = this.order.id;
                this.getUpsShippingRatesCall(params, this.getUpsShippingRatesCallback);
            },
            getUpsShippingRatesCallback(code, data, error) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.order.total_amount = data.order.total_amount;
                    this.order.total_tax_amount = data.order.total_tax_amount;
                    this.current_shipping_methods = Object.assign({}, this.current_shipping_methods, data.available_shipping_methods);
                    if (Object.keys(this.current_shipping_methods).length == 0) {
                        this.empty_msg = true;
                    } else {
                        this.empty_msg = false;
                    }
                    this.$nextTick(function () {
                        $('#radio_shipping03').iCheck('check');
                    });

                    this.resetAddressErrors();
                    this.order

                } else if (code == '110208') {
                    this.error.shipping_country = true;
                    this.error_message.shipping_country = 'Country code not supported';
                }
                else if (code == '111286') {
                    this.error.shipping_state = true;
                    this.error_message.shipping_state = data[0];
                }
                else if (code == '111285') {
                    this.error.shipping_zip = true;
                    this.error_message.shipping_zip = data[0];
                }
                else if (code == '110007') {
                    swal('Information', error[0], 'info');
                    this.current_shipping_methods = Object.assign({});
                }
                else if (code == '002') {
                    this.current_shipping_methods = Object.assign({});
                    this.empty_msg = true;
                }
            },
            getStates() {
                $.LoadingOverlay("show");
                this.getStatesCall(this.getStatesCallback);
            },
            getCountriesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.countries = data;
                    // this.shipping_address.shipping_country = '';
                    // $("#select2-countries").val('US').trigger('change');
                }
                this.showAddAddress = true;
                this.resetAddress();
                this.current_shipping_methods = Object.assign({});
            },
            getStatesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                this.shipping_address.shipping_state = "";
                if (code == '200') {
                    this.states = data;
                }
            },
            showDiscount(){
                if(this.shipping_address.shipping_address != '' && this.shipping_address.shipping_city != ''
                    && this.shipping_address.shipping_state != '' && this.shipping_address.shipping_country != ''){
                    this.show_discount = true;
                    if(Object.keys(this.current_shipping_methods).length <= 0){
                        this.checkDiscount();
                    }
                    
                }else{
                    this.show_discount = false;
                }
                this.error.coupon = false;
        },
        checkDiscount(){
            if(this.discount_code != '' || this.order.discount_code != ''){
                $.LoadingOverlay("show");
                var order = {
                    id: this.order.id,
                    total_amount: parseFloat(this.order.total_amount),
                    shipping_city: this.shipping_address.shipping_city,
                    shipping_state: this.shipping_address.shipping_state,
                    shipping_country: this.shipping_address.shipping_country,
                    carrier: this.carrier,
                    order_items: this.cart_items,
                    customer_email: this.customer_email
                }
                if(this.discount_code != ''){
                    this.validateDiscount(this.discount_code, order, this.validateDiscountCallback);
                }else{
                    this.validateDiscount(this.order.discount_code, order, this.validateDiscountCallback);
                }
            }
        },
        validateDiscountCallback(response){
            this.coupon_message = '';
            $.LoadingOverlay("hide");
            if(response.status > 0){
                this.error.coupon = false;
                this.order.total_amount= parseFloat(response.data.order.total_amount);
                this.order.subtotal= parseFloat(response.data.order.subtotal);
                this.order.total_tax_amount= (response.data.order.total_tax_amount) ? parseFloat(response.data.order.total_tax_amount): 0.00;
                this.order.shipping_cost_estimated= this.shipping_fee;
                this.order.discount_amount= (response.data.order.discount_amount) ? parseFloat(response.data.order.discount_amount) : 0.00;
                this.order.discount_code= (response.data.order.discount_code) ? response.data.order.discount_code : '';
                this.paintCoupon(response.data.discount);
                this.discount_code = '';
            }else{
                this.invalidDiscount(response);
            }
        },
        invalidDiscount(response){
                if(this.order.discount_code != '')
                    //     this.discount_code = this.order.discount_code;
                    // this.error.coupon = true;
                    // this.error_message.coupon = response.errors[0];
                    // this.order.discount_amount= 0.00;
                    // this.order.total_amount= parseFloat(response.data.total_amount),
                    // this.order.total_tax_amount= (response.data.total_tax_amount) ? parseFloat(response.data.total_tax_amount): 0.00;
                    // this.order.shipping_cost_estimated= (response.data.shipping_cost_estimated) ? parseFloat(response.data.shipping_cost_estimated) : 0.00;
                    // this.order.discount_code= '';
                    this.order.total_amount= (response.data.total_amount) ? parseFloat(response.data.total_amount): this.order.total_amount + this.order.discount_amount;
                    this.order.discount_amount= 0.00;
                    this.order.total_tax_amount= (response.data.total_tax_amount) ? parseFloat(response.data.total_tax_amount): this.order.total_tax_amount;
                    this.order.shipping_cost_estimated= (response.data.shipping_cost_estimated) ? parseFloat(response.data.shipping_cost_estimated) : this.order.shipping_cost_estimated;
                    this.order.discount_code= '';
                    this.error_message.coupon = response.errors[0];
                    this.error.coupon = true;
                    // swal('Error', response.errors[0], 'error');
            },
            modifyShippingCostCallback(response){
                $.LoadingOverlay("hide");
                if(response.status > 0){
                    this.order.total_amount= parseFloat(response.data.total_amount),
                    this.order.shipping_cost_estimated= (response.data.shipping_cost_estimated) ? parseFloat(response.data.shipping_cost_estimated) : 0.00;
                }else{
                    swal('Error', response.errors[0], 'error');
                }
            },
            paintCoupon(discount){
                switch(discount.discount_type){
                    case 'percentage':
                        var off = (discount.discount_value % 1 != 0) ? discount.discount_value : (discount.discount_value*1).toFixed();
                        var text = (discount.apply_to != 'order') ? ' on selected items' : '';
                        this.coupon_message = off + '% off' + text;
                        break;
                    case 'fixed_amount':
                        var off = (discount.discount_value % 1 != 0) ? discount.discount_value : (discount.discount_value*1).toFixed();
                        var text = (discount.apply_to != 'order') ? ' on selected items' : '';
                        this.coupon_message = '$ '+ off + ' off' + text;
                        break;
                    case 'free_shipping':
                        this.coupon_message = 'Free shipping';
                        break;
                    case 'buy_x_get_y':
                        var off = (discount.discount_value > 0) ? (discount.discount_value % 1 != 0) ? discount.discount_value : (discount.discount_value*1).toFixed() : '';
                        var text1 = (discount.discount_value*1 < 100) ? ' at ' : '';
                        var text2 = (discount.discount_value*1 < 100) ? '% off ' : '';
                        var text3 = (discount.get_item_id*1 > 0) ? ' on selected items ' : '';
                        var off = (discount.discount_value*1 < 100) ? off : ' free';
                        this.coupon_message = 'Buy '+ discount.buy_qty + ' get '+ discount.get_qty+ text1 + off + text2 + text3;
                        break;
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
            removeItem(item_id) {
                $.LoadingOverlay("show");
                this.removeItemCall(item_id, this.removeItemHandler);
            },
            removeItemHandler(code, data, item_id) {
                if (this.cart_items.length > 0) {
                    for (var i in this.cart_items) {
                        if (this.cart_items[i].id == item_id) {
                            this.cart_items.splice(i, 1);
                        }
                    }
                }
                if(this.cart_items.length > 0){
                    // location.reload();
                    window.location.href = '/cart/checkout';
                }else{
                    window.location.href = '/cart';
                }
            },
    },
        computed: {
            general_total() {
                return this.getTotal();
            }
        },
        watch: {
            'selected_payment_method_id'(val) {
                for (var i in this.payment_methods) {
                    if (this.payment_methods[i].id == val) {
                        this.selected_payment_method_name = this.payment_methods[i].name;
                        if (this.payment_methods[i].name == 'credit-card') {
                            this.showBillingAddress = true;
                        } else {
                            this.showBillingAddress = false;
                        }
                    }
                }
                if (val >= 1) {
                    this.selected_customer_method_id = '';
                    this.error.payment_method = false;
                    this.error_message.payment_method = '';
                }
            },
            'selected_customer_method_id'(val) {
                for (var i in this.current_account.customer_profile.payment_profiles) {
                    if (this.current_account.customer_profile.payment_profiles[i].id == val) {
                        this.selected_payment_method_name = 'profile';
                    }
                }
                if (val >= 1) {
                    this.selected_payment_method_id = '';
                    this.error.payment_method = false;
                    this.error_message.payment_method = '';
                }
            },

            //ERRORS RESET
            'shipping_address.shipping_address'(val) {
                if (val != '') {
                    this.error.shipping_address = false;
                    this.error_message.shipping_address = '';
                    this.showDiscount();
                }
            },
            'shipping_address.shipping_city'(val) {
                if (val != '') {
                    this.error.shipping_city = false;
                    this.error_message.shipping_city = '';
                    this.showDiscount();
                    if(this.shipping_state != '' && this.shipping_country != ''){
                        this.getTaxes();
                    }
                }
            },
            'shipping_address.shipping_zip'(val) {
                if (val != '') {
                    this.error.shipping_zip = false;
                    this.error_message.shipping_zip = '';
                    this.showDiscount();
                    if(this.shipping_state != '' && this.shipping_country != '' && val.length >= 5){
                        this.getShippingMethodsOnAddressChange();
                    }
                }
            },
            'shipping_address.shipping_state'(val) {
                if (val != '') {
                    this.error.shipping_state = false;
                    this.error_message.shipping_state = '';
                    this.showDiscount();
                    if(this.shipping_city != '' && this.shipping_country != ''){
                        this.getTaxes();
                    }
                }
            },
            'shipping_address.shipping_country'(val) {
                if (val != '') {
                    this.error.shipping_country = false;
                    this.error_message.shipping_country = '';
                    this.showDiscount();
                    if(this.shipping_state != '' && this.shipping_city != ''){
                        this.getTaxes();
                    }
                }
            },
            'billing_address.billing_address'(val) {
                if (val != '') {
                    this.error.billing_address = false;
                    this.error_message.billing_address = '';
                }
            },
            'billing_address.billing_city'(val) {
                if (val != '') {
                    this.error.billing_city = false;
                    this.error_message.billing_city = '';
                }
            },
            'billing_address.billing_zip'(val) {
                if (val != '') {
                    this.error.billing_zip = false;
                    this.error_message.billing_zip = '';
                }
            },
            'billing_address.billing_state'(val) {
                if (val != '') {
                    this.error.billing_state = false;
                    this.error_message.billing_state = '';
                }
            },
            'billing_address.billing_country'(val) {
                if (val != '') {
                    this.error.billing_country = false;
                    this.error_message.billing_country = '';
                }
            },
            'cardInfo.card_holder'(val) {
                if (val != '') {
                    this.error.card_holder = false;
                    this.error_message.card_holder = '';
                }
            },
            'cardInfo.card_number'(val) {
                if (val != '') {
                    this.error.card_number = false;
                    this.error_message.card_number = '';
                }
            },
            // 'cardInfo.exp_date'(val) {
            //     if (val != '') {
            //         this.error.exp_date = false;
            //         this.error_message.exp_date = '';
            //     }
            //     if (Helpers.validateExpDateMMYYYY(val)) {
            //         this.error.exp_date = false;
            //     } else {
            //         this.error.exp_date = true;
            //     }
            // },
            'cardInfo.exp_month'(val) {
                if (val != '') {
                    this.error.exp_month = false;
                    this.error_message.exp_month = '';
                    $("#select2-months").select2({
                        containerCssClass: "input-bordered"
                    });
                }
            },
            'cardInfo.exp_year'(val) {
                if (val != '') {
                    this.error.exp_year = false;
                    this.error_message.exp_year = '';
                    $("#select2-years").select2({
                        containerCssClass: "input-bordered"
                    });
                }
            },
            'cardInfo.cvv'(val) {
                if (this.val != '') {
                    this.error.cvv = false;
                    this.error_message.cvv = '';
                }
                if (Helpers.validateCVV(val)) {
                    this.error.cvv = false;
                } else {
                    this.error.cvv = true;
                }
            },
            'paypalEmail'(val) {
                if (val != '') {
                    this.error.paypalEmail = false;
                    this.error_message.paypalEmail = '';
                }
            },
            'customer_email'(val) {
                if (val != '') {
                    this.error.customer_email = false;
                    this.error_message.customer_email = '';
                }
            },
            'customer_name'(val) {
                if (val != '') {
                    this.error.customer_name = false;
                    this.error_message.customer_name = '';
                }
            },
            'discount_code'(val) {
                if (val != '') {
                    this.enabled_apply = false;
                }else{
                    this.enabled_apply = true;
                }
             }

        },
        created() {
            var date = new Date,
            years = [],
            year = date.getFullYear();

            for (var i = year; i < year + 20; i++) {
                this.years.push(i);
            }
            if (this.items) {
                this.fillCartItem(this.items);
            } else {
                window.location.href = '/';
            }
            if (this.account) {
                this.current_account = this.account;
                if (this.current_account.customer_profile) {
                    this.customer_payment_methods = this.current_account.customer_profile.payment_profiles;
                }
            } else {
                this.showNewAddress();
            }
            if (Object.keys(this.available_shipping_methods).length > 0) {
                this.current_shipping_methods = Object.assign({}, this.current_shipping_methods, this.available_shipping_methods);
            }

            if(this.torder.id){
                this.order.id = this.torder.id 
                this.order.total_amount= parseFloat(this.torder.total_amount),
                this.order.subtotal= parseFloat(this.torder.subtotal),
                this.order.total_tax_amount= (this.torder.total_tax_amount) ? parseFloat(this.torder.total_tax_amount): 0.00;
                this.order.shipping_cost_estimated= (this.torder.shipping_cost_estimated) ? parseFloat(this.torder.shipping_cost_estimated) : 0.00;
                this.order.discount_amount= (this.torder.discount_amount) ? parseFloat(this.torder.discount_amount) : 0.00;
                this.order.discount_code= (this.torder.discount_code) ? this.torder.discount_code : '';
                if(this.order.discount_code != ''){
                    
                    this.paintCoupon(this.discount);
                }
                this.order.shipping_address_1= (this.torder.shipping_address != null) ? this.torder.shipping_address : '';
                this.order.shipping_address_2= (this.torder.shipping_address_cont) ? this.torder.shipping_address_cont : '';
                this.order.shipping_city= (this.torder.shipping_city) ? this.torder.shipping_city : '';
                this.order.shipping_zip= (this.torder.shipping_zip) ? this.torder.shipping_zip : '';
                this.order.shipping_state= (this.torder.shipping_state) ? this.torder.shipping_state : '';
                this.order.shipping_country= (this.torder.shipping_country) ? this.torder.shipping_country : '';
                this.order.carrier= (this.torder.carrier) ? this.torder.carrier : '';

                this.order.billing_address = (this.torder.billing_address) ? this.torder.shipping_address : '';
                this.order.billing_city = (this.torder.billing_city) ? this.torder.billing_city : '';
                this.order.billing_zip = (this.torder.billing_zip) ? this.torder.billing_zip : '';
                this.order.billing_state = (this.torder.billing_state) ? this.torder.billing_state : '';
                this.order.billing_country = (this.torder.billing_country) ? this.torder.billing_country : '';

                this.order.payment_method_id = (this.torder.payment_method_id != '') ? this.torder.payment_method_id : '';
                this.order.selected_shipping_method_code = (this.torder.shipping_method_code) ? this.torder.shipping_method_code : '';
                
            }
        },
        mounted() {
            if (this.card_processor.toLowerCase() == 'stripe') {
                Stripe.setPublishableKey(this.client_key);
            }
            var self = this;
            this.$nextTick(function () {

                $("#select2-countries").select2({
                    matcher: self.matchCustom
                });
                $("#select2-states").select2({
                    matcher: self.matchCustom
                });

                $('#select2-countries').on('change', function (e) {
                    self.current_shipping_methods = Object.assign({});
                    self.shipping_address.shipping_country = $('#select2-countries').select2('data')[0].id;
                    if (self.shipping_address.shipping_country == 'US') {
                        self.getStates();
                    }
                    if (self.shipping_address.shipping_country != '') {
                        $(".select2-selection").removeClass("select2-error");
                    }
                });

                $('#select2-states').on('change', function (e) {
                    self.shipping_address.shipping_state = $('#select2-states').select2('data')[0].id;
                    if (self.shipping_address.shipping_state != '') {
                        $(".select2-selection").removeClass("select2-error");
                    }
                });

                $('#cc_number').validateCreditCard(function (result) {
                    if (this.val() == "") {
                        self.valid_card = true;
                        self.card_brand = '';
                    } else {
                        if (result.length_valid == true) {
                            if (result.valid == true) {
                                if (result.luhn_valid == true) {
                                    self.valid_card = true;
                                    if (result.card_type.name == 'visa') {
                                        self.card_brand = '/images/creditCard/visa.png';
                                    } else if (result.card_type.name == 'mastercard') {
                                        self.card_brand = '/images/creditCard/mastercard.png';
                                    } else if (result.card_type.name == 'amex') {
                                        self.card_brand = '/images/creditCard/amex.png';
                                    } else if (result.card_type.name == 'discover') {
                                        self.card_brand = '/images/creditCard/discover.png';
                                    }
                                }
                            }
                        } else {
                            self.valid_card = false;
                            self.card_brand = '';
                        }
                    }
                });
                if (self.current_account) {
                    if (this.current_account.addresses.length > 0) {
                        for (var i in this.current_account.addresses) {
                            if (this.current_account.addresses[i].default == 1) {
                                $('#radio_address' + this.current_account.addresses[i].id).iCheck('check');
                                this.fillShippingAddress(this.current_account.addresses[i].id);
                                break;
                            }
                        }
                    }
                }
                setTimeout(function () {
                    $('.shipping_methods').on('ifChecked', function (event) {
                        self.selected_shipping_method_code = event.target.value;
                        self.shipping_fee = self.ifCheckCallback(event.target.value);
                        self.order.shipping_cost_estimated = self.shipping_fee;
                    });
                }, 50);

                if(this.order.selected_shipping_method_code != ''){
                    $('#radio_shipping'+this.torder.shipping_method_code).iCheck('check');
                }else{
                    $('#radio_shipping03').iCheck('check');
                }
            });
        },
        updated() {
            var self = this;
            this.$nextTick(function () {
                if (self.billingAddress) {
                    $('#shippingAsBilling').iCheck('check');
                }

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-green',
                });

                $('#saveShipping').on('ifChecked', function (event) {
                    self.saveAddress = true;
                });

                $('#saveShipping').on('ifUnchecked', function (event) {
                    self.saveAddress = false;
                });

                $('#shippingAsBilling').on('ifChecked', function (event) {
                    self.billingAddress = true;
                });

                $('#shippingAsBilling').on('ifUnchecked', function (event) {
                    self.billingAddress = false;
                });

                if (self.current_account) {
                    if (self.current_account.addresses.length > 0) {
                        setTimeout(function () {
                            $('input.shipping_address').on('ifChecked', function (event) {
                                self.fillShippingAddress(event.target.value);
                                self.getShippingMethodsOnAddressChange();
                            });
                        }, 50);
                    }
                }

                if (Object.keys(self.current_shipping_methods).length > 0) {
                    setTimeout(function () {
                        $('.shipping_methods').on('ifChecked', function (event) {
                            self.selected_shipping_method_code = event.target.value;
                            self.shipping_fee = self.ifCheckCallback(event.target.value);
                            self.order.shipping_cost_estimated = self.shipping_fee;
                        });
                    }, 50);
                }
            });

            $('#select2-months').on('change', function (e) {
                self.cardInfo.exp_month = $('#select2-months').val();
            });
            $('#select2-years').on('change', function (e) {
                self.cardInfo.exp_year = $('#select2-years').val();
            });
        }
    }
</script>
<style>
    .tt-counter.tt-counter__inner {
        width: 110px;
    }

    .input-bordered {
        border: 1px solid rgb(194, 191, 191) !important;
    }

    .input-bordered-error {
        border: 2px dashed rgb(190, 20, 20) !important;
    }

    .input-error-msg {
        color: rgb(190, 20, 20) !important;
        float: right !important;
    }

    .radio-pay-ch {
        background-color: #1b7d5a !important;
    }

    .radio-pay-uch {
        background-color: #fff !important;
    }

    .tt-checkbox-circle span {
        width: 24px;
        height: 24px;
    }

    .tt-checkout__payment ul li label {
        color: #fff;
    }

    .tt-checkbox-circle input:checked+span::before {
        margin-right: .4em !important;
    }

    .disable_span {
        pointer-events: none !important;
    }

    .enable_span {
        pointer-events: auto !important;
    }

    .select2-selection {
        height: 50px !important;
        background-color: #f5f5f5 !important;
    }

    .select2-selection__placeholder {
        margin-top: 0px !important;
    }

    .select2-selection__rendered {
        color: #13919b !important;
        padding-left: 20px !important;
        margin-top: 10px !important;
    }

    .select2-selection__placeholder {
        color: #98969c !important;
    }

    .select2-error {
        border-color: rgb(190, 20, 20) !important;
        border-style: dashed !important;
        border-width: 2px !important;
        border-radius: 0px !important;
    }

    .select2-selection__arrow {
        margin-top: 10px !important;
    }
    .cross-out {
     -webkit-text-decoration-line: line-through; /* Safari */
     text-decoration-line: line-through;
     text-decoration-color: rgb(196, 5, 5);
    }
    #menu-cart{
        display: none!important;
    }
    #menu-cart-checkout{
        display: inline-block;
    }

    @media (max-width: 480px){
        .container {
            width: 750px;
            max-width: 100%;
        }
    }

    @media (max-width: 1024px){
        .container {
            width: 800px;
            max-width: 100%;
        }
    }
</style>