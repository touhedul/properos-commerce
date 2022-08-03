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
                            <a :href="'/my-account/' + current_account.id">My account</a>
                        </li>
                        <!--  <li>
                            <span>Audio</span>
                        </li> -->
                    </ul>
                </div>
                <div class="text-center">
                    <h3>My Account</h3>
                </div>

                <div class="tt-my-account">
                    <div class="tt-my-account__tabs tt-tabs tt-layout__mobile-full" data-tt-type="vertical">
                        <div class="tt-tabs__head">
                            <div class="tt-tabs__slider">
                                <div @click="initComponents()" class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && (window.location.href.split('#')[1] == 'orders' || window.location.href.split('#')[1] == 'profile')) ? false : true)">
                                    <span>Dashboard</span>
                                </div>
                                <div @click="initComponents()" class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && (window.location.href.split('#')[1] == 'profile')) ? true : false)">
                                    <span>Profile & Security</span>
                                </div>
                                <div @click="initComponents()" class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && window.location.href.split('#')[1] == 'orders') ? true : false)">
                                    <span>Orders</span>
                                </div>
                                <div @click="initComponents()" class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && window.location.href.split('#')[1] == 'shipping_methods') ? true : false)">
                                    <span>Payments Methods</span>
                                </div>
                                <div @click="initComponents()" class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && window.location.href.split('#')[1] == 'shipping_address') ? true : false)">
                                    <span>Shipping Addresses</span>
                                </div>
                                <!-- <div class="tt-tabs__btn" :data-active="((window.location.href.split('#')[0] && window.location.href.split('#')[1] == 'membership') ? true : false)">
                                    <span>Membership</span>
                                </div> -->
                            </div>
                            <div class="tt-tabs__btn-prev"></div>
                            <div class="tt-tabs__btn-next"></div>
                            <div class="tt-tabs__border"></div>
                        </div>
                        <div class="tt-tabs__body tt-tabs-my-account">
                            <div>
                                <span>Dashboard
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <p class="ttg-mb--20">Hello {{current_account.firstname}}</p>
                                    <p>From your account dashboard you can view your
                                        <a href="#">recent orders,</a>
                                        manage your
                                        <a href="#">and shipping addresses</a> and
                                        <a href="#">edit your password and account details.</a>
                                    </p>
                                    <div class="row">
                                        <div class="col-md-4" style="margin-top: 2px">
                                            <div class="card border-primary text-center bg-transparent" style="height: 60.75px; width: 100%">
                                                <div class="card-content">
                                                    <a style="color: #333;cursor:pointer;" @click="goTo('orders')">
                                                        <span></span>Total orders
                                                        <div>
                                                            <b>{{paginator.total}}</b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 2px">
                                            <div class="card border-primary text-center bg-transparent" style="height: 60.75px; width: 100%">
                                                <div class="card-content">
                                                    <a style="color: #333;cursor:pointer;" @click="goTo('shipping_address')">
                                                        Registered address
                                                        <div>
                                                            <b>{{current_addresses.length}}</b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="margin-top: 2px">
                                            <div class="card border-primary text-center bg-transparent" style="height: 60.75px; width: 100%">
                                                <div class="card-content">
                                                    <a style="color: #333;cursor:pointer;" @click="goTo('shipping_methods')">
                                                        Payment methods
                                                        <div>
                                                            <b>{{current_payment_methods.length}}</b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  <div class="col-md-4">
                                            <div class="card border-primary text-center bg-transparent" style="height: 60.75px; width: 200px">
                                                <div class="card-content">
                                                    Total orders
                                                    <div>
                                                        <b>45</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>

                                </div>
                            </div>
                            <div>
                                <span>Profile & Security
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div class="tt-form">
                                        <div class="tt-form__form">
                                            <label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span class="ttg__required">Name:</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Enter your firstname" v-model="current_account.firstname" :class="{'input-bordered-error' : error.firstname}">
                                                        <div v-if="error.firstname" class="input-error-msg">{{error_message.firstname}}</div>
                                                    </div>
                                                </div>
                                            </label>
                                            <label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span class="ttg__required">Last Name:</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control colorize-theme6-bg" v-model="current_account.lastname" placeholder="Enter please your last name"
                                                            :class="{'input-bordered-error' : error.lastname}">
                                                        <div v-if="error.lastname" class="input-error-msg">{{error_message.lastname}}</div>
                                                    </div>
                                                </div>
                                            </label>
                                            <label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span class="ttg__required">Email:</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Enter your email" v-model="current_account.email"
                                                            :class="{'input-bordered-error' : error.email}">
                                                        <div v-if="error.email" class="input-error-msg">{{error_message.email}}</div>
                                                    </div>
                                                </div>
                                            </label>
                                            <label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>Phone:</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Enter your phone" v-model="current_account.phone">
                                                    </div>
                                                </div>
                                            </label>
                                            <label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <span>Company:</span>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control colorize-theme6-bg" placeholder="Enter your company" v-model="current_account.company">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div style="text-align:right;">
                                            <button @click="update()" class="btn ttg-mt--40">Update</button>
                                        </div>
                                    </div>
                                    <div class="tt-tabs-my-account__form ttg-mt--40">
                                        <div class="tt-tabs-my-account__form_title">Password Change</div>
                                        <!-- <p>Current password (leave blank to leave unchanged)</p> -->
                                        <input v-model="user_security_info.old_pass" type="password" class="form-control" placeholder="Enter your current password"
                                            :class="{'input-bordered-error' : error.old_pass}">
                                        <div v-if="error.old_pass" class="input-error-msg">{{error_message.old_pass}}</div>
                                        <!-- <p>Current password (leave blank to leave unchanged)</p> -->
                                        <input v-model="user_security_info.new_pass" type="password" class="form-control" placeholder="Enter your new password" :class="{'input-bordered-error' : error.new_pass}">
                                        <div v-if="error.new_pass" class="input-error-msg">{{error_message.new_pass}}</div>
                                        <!-- <p>Current password (leave blank to leave unchanged)</p> -->
                                        <input v-model="user_security_info.new_pass_conf" type="password" class="form-control" placeholder="Confirm your new password"
                                            :class="{'input-bordered-error' : error.new_pass_conf}">
                                        <div v-if="error.new_pass_conf" class="input-error-msg">{{error_message.new_pass_conf}}</div>
                                    </div>
                                    <button @click="changePassword()" class="btn ttg-mt--40">Change Password</button>
                                </div>
                            </div>
                            <div>
                                <span>Orders
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div v-if="!order_details">
                                        <div v-if="current_orders.length > 0" class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table">
                                                <tr>
                                                    <th>Order number</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                </tr>
                                                <tr v-for="(current_order, index) in current_orders" :key="index">
                                                    <td>
                                                        <a href="#">{{current_order.order_number}}</a>
                                                    </td>
                                                    <td>{{moment(current_order.created_at).format('MMM DD, YYYY')}}</td>
                                                    <td>{{current_order.status}}</td>
                                                    <td>$ {{current_order.paid_amount}}</td>
                                                    <td class="text-right">
                                                        <a @click="showOrderDetails(current_order.id)" href="#">View Details</a>
                                                    </td>
                                                </tr>
                                            </table>
                                            <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="getOrders()" :offset="offset"></order-paginator>
                                        </div>
                                        <div v-else>
                                            <div style="font-size: 18px">
                                                Currently there are no orders placed
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="order_details">
                                        <a @click="hideOrderDetails()" href="#" style="float: right">Back to orders</a>
                                        <h5>Order Details</h5>
                                        <p class="ttg-mt--30 ttg-mb--30">
                                            <b>Order#</b>
                                            <a>
                                                <b style="color: #13919b">{{order_details_data.order_number}}</b>
                                            </a> was placed on {{moment(order_details_data.created_at).format('MMM DD, YYYY')}}
                                        </p>
                                        <p v-if="order_details_data.shipping_status" class="ttg-mt--30 ttg-mb--30">
                                            <b>Order status:</b>
                                            <a>
                                                {{order_details_data.shipping_status | capitalize}}
                                            </a>
                                        </p>
                                        <template v-if="order_details_data.shipping_status == 'shipped'">
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <b>Shipped through:</b>
                                                <a>
                                                    {{order_details_data.shipping_method.label}}
                                                    <img v-if="order_details_data.shipping_method.name == 'usps' ||
                                                    order_details_data.shipping_method.name == 'fedex' ||
                                                    order_details_data.shipping_method.name == 'dhl'" :src="'images/carriers/' + order_details_data.shipping_method.name + '.jpg'"
                                                        style="width: 60px; height: auto">
                                                    <img v-else-if="order_details_data.shipping_method.name == 'ups'" :src="'images/carriers/' + order_details_data.shipping_method.name + '.png'"
                                                        style="width: 60px; height: auto">
                                                </a>
                                            </p>
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <b>Tracking number:</b>
                                                <a>
                                                    {{order_details_data.shipping_tracking}}
                                                </a>
                                            </p>
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <a v-if="order_details_data.shipping_method.name == 'usps'" target="blank" :href="'https:tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'ups'" target="blank" :href="'https:wwwapps.ups.com/tracking/tracking.cgi?tracknum=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'fedex'" target="blank" :href="'https:www.fedex.com/apps/fedextrack/?action=track&trackingnumber=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'dhl'" target="blank" :href="'http:www.dhl.com/en/express/tracking.html?brand=DHL&AWB=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                            </p>
                                        </template>
                                        <hr>
                                        <div class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table tt-tabs-my-account--table-sm">
                                                <tr>
                                                    <th>Products</th>
                                                    <th>Price</th>
                                                </tr>
                                                <tr v-if="order_details_data.products.length" v-for="(product, index) in order_details_data.products" style="border-bottom: 1px solid rgb(212, 211, 211)"
                                                    :key="index">
                                                    <td>
                                                        <img v-if="product.item.files" :src="product.item.files.length > 0 ? '/storage/' + product.item.files[0].thumb_path : '/images/item-placeholder.jpg'"
                                                            style="width: 150px; height: auto"><br>
                                                        <a style="color: rgb(167, 164, 164)">{{product.name}}</a> x {{(product.qty % 1 != 0)?product.qty:parseInt(product.qty)}}
                                                    </td>
                                                    <td>$ {{product.price}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Subtotal:</td>
                                                    <td>$ {{(order_details_data.subtotal*1).toFixed(2)}}</td>
                                                </tr>
                                                <tr v-if="order_details_data.discount_amount != null && order_details_data.discount_amount > 0">
                                                    <td class="ttg-color--gray-dark">Discount:</td>
                                                    <td>$ {{(order_details_data.discount_amount*1).toFixed(2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Taxes:</td>
                                                    <td>$ {{(order_details_data.total_tax_amount*1).toFixed(2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Shipping</td>
                                                    <td v-if="order_details_data.shipping_cost_estimated != null && order_details_data.shipping_cost_estimated == 0">Free </td>
                                                    <td v-else>$ {{(order_details_data.shipping_cost_estimated*1).toFixed(2)}} </td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Payment Method:</td>
                                                    <td>Credit/Debit Card</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Total:</td>
                                                    <td style="font-size: 18px">$ {{(order_details_data.total*1).toFixed(2)}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <h5>Shipping Address</h5>
                                        <div class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table tt-tabs-my-account--table-sm" style="border-bottom: 1px solid rgb(230, 228, 228)">
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Address:</td>
                                                    <td>{{order_details_data.address1}} {{order_details_data.address2}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Town/City:</td>
                                                    <td>{{order_details_data.city}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">State/Province:</td>
                                                    <td>{{order_details_data.state}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Postode/ZIP:</td>
                                                    <td>{{order_details_data.zip}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Country:</td>
                                                    <td>{{order_details_data.country}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <h5>Tracking Information</h5>
                                        <div v-if="tracking_info.tracking_number" class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table tt-tabs-my-account--table-sm" style="border-bottom: 1px solid rgb(230, 228, 228)">
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Shipping type:</td>
                                                    <td>{{tracking_info.shipping_type}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Shipping service:</td>
                                                    <td>{{tracking_info.shipping_service}}</td>
                                                </tr>
                                            </table>
                                            <template v-if="tracking_info.activities.length > 0">
                                                <div style="color: #000">
                                                    <b>Shipping History</b>
                                                </div>
                                                <ul>
                                                    <li v-for="(activity, index) in tracking_info.activities" :key="index">
                                                        <div>
                                                            <span style="font-size: 13px; font-weight: bold">
                                                                Date: {{moment(activity.date + ' ' + activity.time).format('MMMM Do YYYY, h:mm:ss a')}} </span>
                                                        </div>
                                                        <table class="table tt-tabs-my-account--table-sm" style="border-bottom: 1px solid rgb(230, 228, 228)">
                                                            <tr v-if="activity.location_city">
                                                                <td class="ttg-color--gray-dark" style="width: 20%">Location :</td>
                                                                <td>
                                                                    <span style="font-size: 14px">{{activity.location_city}} {{activity.location_zip}}
                                                                        {{activity.location_state}} {{activity.location_country}}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ttg-color--gray-dark" style="width: 20%">Status :</td>
                                                                <td>
                                                                    <span class="delivered" style="font-size: 14px" :class="{'pending' : activity.status != 'DELIVERED'}">{{activity.status}}
                                                                        <span class="fa fa-check" :class="{'fa fa-clock-o' : activity.status != 'DELIVERED'}"></span>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </li>
                                                </ul>
                                            </template>
                                        </div>
                                        <div v-else>
                                            <span>There is still no tracking information available</span>
                                        </div>
                                    </div>
                                    <!-- <template v-if="current_orders.length > 0">
                                        <a href="#" class="btn">Previous</a>
                                        <a href="#" class="btn">Next</a>
                                    </template> -->
                                </div>
                            </div>
                            <div>
                                <span>Payment Methods
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div>
                                        <div class="row" style="text-align: right;">
                                            <div class="col-md-12">
                                                <a v-if="!showAddCard" @click="showAddPaymentmethod()" class="btn">Add payment method</a>
                                            </div>
                                        </div>
                                        <template v-if="current_payment_methods.length > 0 && !showAddCard">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div style="font-size: 16px">
                                                        Your current payment methods
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="margin-top: 10px">
                                                <!-- <div class="col-md-12"> -->
                                                <div v-for="(method, index) in current_payment_methods" :key="index" class="card border-primary text-center bg-transparent"
                                                    style="height: 100%; width: 100% !important; text-align: left !important; margin-top: 30px; padding: 10px; border-color: #dbdedf !important">
                                                    <div class="card-content">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-2">
                                                                <span>
                                                                    <img v-if="method.brand == 'MasterCard'" src="/images/creditCard/mastercard.png" style="width: 60px; height: auto;">
                                                                    <img v-else-if="method.brand == 'AMEX'" src="/images/creditCard/amex.png" style="width: 60px; height: auto;">
                                                                    <img v-else-if="method.brand == 'Discover'" src="/images/creditCard/discover.png" style="width: 60px; height: auto;">
                                                                    <img v-else-if="method.brand == 'Visa'" src="/images/creditCard/visa.png" style="width: 60px; height: auto;">
                                                                </span>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-5">
                                                                <span>
                                                                    <span>{{method.brand}} ending in</span>
                                                                    <span>
                                                                        <b>{{method.last_numbers}}</b>
                                                                    </span>
                                                                    <!--  <div>
                                                                        Expires
                                                                    </div> -->
                                                                </span>
                                                            </div>
                                                            <div class="col-xs-3 col-sm-3 d-none d-md-block">
                                                                <span>
                                                                    Expires: {{method.expiration_date}}
                                                                </span>
                                                            </div>
                                                            <!--  <div class="col-xs-1">
                                                                <span>
                                                                    Edit
                                                                </span>
                                                            </div> -->
                                                            <div class="col-xs-12 col-sm-2">
                                                                <span style="float:  right">
                                                                    <a @click="editPaymentMethod(method.id)" style="cursor: pointer;color:#13919b;">Edit</a>
                                                                    <a @click="removePaymentMethod(method.id)" style="cursor: pointer;color:#FF7588;">Delete</a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  </div> -->
                                            </div>
                                        </template>
                                        <div v-else>
                                            <div style="font-size: 16px">
                                                Currently there are no payment methods saved
                                            </div>
                                        </div>
                                        <div v-show="showAddCard" style="margin-top: 30px">
                                            <div>
                                                <card-processor-component :editPayment="paymentId" :clientKey="client_key" :apiLoginID="api_id" :cardProcessor="card_processor" @addPaymentMethod="addPaymentMethodHandle"
                                                    :description="description"></card-processor-component>
                                            </div>
                                        </div>

                                    </div>
                                    <div v-if="order_details">
                                        <a @click="hideOrderDetails()" href="#" style="float: right">Back to orders</a>
                                        <h5>Order Details</h5>
                                        <p class="ttg-mt--30 ttg-mb--30">
                                            <b>Order#</b>
                                            <a>
                                                <b style="color: #13919b">{{order_details_data.order_number}}</b>
                                            </a> was placed on {{moment(order_details_data.created_at).format('MMM DD, YYYY')}}
                                        </p>
                                        <p v-if="order_details_data.shipping_status" class="ttg-mt--30 ttg-mb--30">
                                            <b>Order status:</b>
                                            <a>
                                                {{order_details_data.shipping_status | capitalize}}
                                            </a>
                                        </p>
                                        <template v-if="order_details_data.shipping_status == 'shipped'">
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <b>Shipped through:</b>
                                                <a>
                                                    {{order_details_data.shipping_method.label}}
                                                    <img v-if="order_details_data.shipping_method.name == 'usps' ||
                                                        order_details_data.shipping_method.name == 'fedex' ||
                                                        order_details_data.shipping_method.name == 'dhl'" :src="'images/carriers/' + order_details_data.shipping_method.name + '.jpg'"
                                                        style="width: 60px; height: auto">
                                                    <img v-else-if="order_details_data.shipping_method.name == 'ups'" :src="'images/carriers/' + order_details_data.shipping_method.name + '.png'"
                                                        style="width: 60px; height: auto">
                                                </a>
                                            </p>
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <b>Tracking number:</b>
                                                <a>
                                                    {{order_details_data.shipping_tracking}}
                                                </a>
                                            </p>
                                            <p class="ttg-mt--30 ttg-mb--30">
                                                <a v-if="order_details_data.shipping_method.name == 'usps'" target="blank" :href="'https:tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'ups'" target="blank" :href="'https:www.apps.ups.com/tracking/tracking.cgi?tracknum=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'fedex'" target="blank" :href="'https:www.fedex.com/apps/fedextrack/?action=track&trackingnumber=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                                <a v-if="order_details_data.shipping_method.name == 'dhl'" target="blank" :href="'http:www.dhl.com/en/express/tracking.html?brand=DHL&AWB=' + order_details_data.shipping_tracking">
                                                    <b>Click here to track your order</b>
                                                </a>
                                            </p>
                                        </template>
                                        <hr>
                                        <div class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table tt-tabs-my-account--table-sm">
                                                <tr>
                                                    <th>Products</th>
                                                    <th>Price</th>
                                                </tr>
                                                <tr v-if="order_details_data.products.length" v-for="(product, index) in order_details_data.products" :key="index" style="border-bottom: 1px solid rgb(212, 211, 211)">
                                                    <td>
                                                        <img v-if="product.item.files" :src="product.item.files.length > 0 ? '/storage/' + product.item.files[0].thumb_path : '/images/item-placeholder.jpg'"
                                                            style="width: 150px; height: auto">
                                                        <a style="color: rgb(167, 164, 164)">{{product.name}}</a> x {{product.qty}}
                                                    </td>
                                                    <td>$ {{product.price}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Subtotal:</td>
                                                    <td>$ {{order_details_data.subtotal}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Taxes:</td>
                                                    <td>$ {{order_details_data.total_tax_amount}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Shipping Method</td>
                                                    <td>Free </td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Payment Method:</td>
                                                    <td>Credit/Debit Card</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Total:</td>
                                                    <td style="font-size: 18px">$ {{order_details_data.total}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <h5>Shipping Address</h5>
                                        <div class="tt-tabs-my-account__table ttg-mb--30">
                                            <table class="table tt-tabs-my-account--table-sm" style="border-bottom: 1px solid rgb(230, 228, 228)">
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Address:</td>
                                                    <td>{{order_details_data.address1}} {{order_details_data.address2}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Town/City:</td>
                                                    <td>{{order_details_data.city}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">State/Province:</td>
                                                    <td>{{order_details_data.state}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Postode/ZIP:</td>
                                                    <td>{{order_details_data.zip}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="ttg-color--gray-dark">Country:</td>
                                                    <td>{{order_details_data.country}}</td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                    <!-- <template v-if="current_orders.length > 0">
                                            <a href="#" class="btn">Previous</a>
                                            <a href="#" class="btn">Next</a>
                                        </template> -->
                                </div>
                            </div>
                            <div>
                                <span>Billing Addresses
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div v-if="!showAddAddress" style="text-align: right">
                                        <a @click="showNewAddress()" href="#" class="btn">Add address</a>
                                    </div>
                                    <p v-if="current_addresses.length > 0" class="ttg-mb--30">The following addresses will be used on the checkout page by default.
                                    </p>
                                    <div v-if="!showAddAddress && current_addresses.length > 0" style="margin-top: 25px">
                                        <template v-for="(current_address, index) in current_addresses">
                                            <div :key="index">
                                                <div class="tt-tabs-my-account__head-edit" style="margin-top: 15px">
                                                    <a v-if="!current_address.default" style="cursor: pointer" @click="setDefaultAddress(current_address.id)">
                                                        <i class="icon-up-circle"></i>Set as default
                                                    </a>
                                                    <a style="color: green">
                                                        <b v-if="current_address.default">Default shipping address</b>
                                                    </a>
                                                    <a style="cursor: pointer" @click="editAddress(current_address)">
                                                        <i class="icon-trash-circled"></i>edit
                                                    </a>
                                                    <a style="cursor: pointer" @click="removeAddress(current_address.id)">
                                                        <i class="icon-trash-circled"></i>remove
                                                    </a>
                                                </div>
                                                <div class="tt-tabs-my-account__table ttg-mb--10" style="border-bottom: 1px solid rgb(230, 228, 228)">
                                                    <table class="table tt-tabs-my-account--table-sm">
                                                        <tr>
                                                            <td class="ttg-color--gray-dark">Address:</td>
                                                            <td>{{current_address.address1}} {{current_address.address2}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ttg-color--gray-dark">Town/City:</td>
                                                            <td>{{current_address.city}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ttg-color--gray-dark">State/Province:</td>
                                                            <td>{{current_address.state}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ttg-color--gray-dark">Postode/ZIP:</td>
                                                            <td>{{current_address.zip}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ttg-color--gray-dark">Country:</td>
                                                            <td>{{current_address.country}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                    <div v-else>
                                        <div v-if="!showAddAddress">
                                            <h5>No address registered</h5>
                                        </div>
                                    </div>
                                    <div v-show="showAddAddress" style="margin-top: 15px">
                                        <div class="tt-form">
                                            <div class="tt-form__form">
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="ttg__required">Country:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <!-- <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.country, 'input-bordered' : !error.country}"
                                                                placeholder="Enter your country" v-model="address.country"> -->
                                                            <!-- <select :class="{'input-bordered-error' : error.country, 'input-bordered' : !error.country}" v-model="address.country"></select> -->
                                                            <select id="select2-countries" class="select2-placeholder form-control input-bordered colorize-theme6-bg" style="height: 50px !important; width: 100%"
                                                                placeholder="Select country...">
                                                                <option :value="-1"></option>
                                                                <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                                            </select>
                                                            <div v-if="error.country" class="input-error-msg">{{error_message.country}}</div>
                                                        </div>
                                                    </div>
                                                </label>
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="ttg__required">Address:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.address, 'input-bordered' : !error.address}"
                                                                placeholder="Enter address" v-model="address.address1">
                                                            <div v-if="error.address" class="input-error-msg">{{error_message.address}}</div>
                                                        </div>
                                                    </div>
                                                </label>
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span>Address Cont.:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control colorize-theme6-bg input-bordered" placeholder="Enter address (continue)" v-model="address.address2">
                                                        </div>
                                                    </div>
                                                </label>
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="ttg__required">City/Town:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.city, 'input-bordered' : !error.city}"
                                                                v-model="address.city" placeholder="Enter city">
                                                            <div v-if="error.city" class="input-error-msg">{{error_message.city}}</div>
                                                        </div>
                                                    </div>
                                                </label>
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="ttg__required">State/Province 2:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div v-show="address.country == 'US'">
                                                                <select id="select2-states-my-account" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%"
                                                                    data-placeholder="Select state...">
                                                                    <option value="-1" selected class="d-none"></option>
                                                                    <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                                                </select>
                                                            </div>
                                                            <input v-show="address.country != 'US'" type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.state, 'input-bordered' : !error.state}"
                                                                placeholder="Enter state" v-model="address.state">
                                                            <div v-if="error.state" class="input-error-msg">{{error_message.state}}</div>
                                                        </div>
                                                    </div>
                                                    <!-- <select id="select2-test" class="select2-placeholder form-control input-bordered" style="height: 50px !important; width: 100%"
                                                        data-placeholder="Select state...">
                                                        <option value="d">dulce</option>
                                                        <option value="d">dulce</option>
                                                    </select> -->
                                                </label>
                                                <label>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <span class="ttg__required">Zip/Postal Code:</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control colorize-theme6-bg" :class="{'input-bordered-error' : error.zip, 'input-bordered' : !error.zip}"
                                                                placeholder="Enter zip code" v-model="address.zip">
                                                            <div v-if="error.zip" class="input-error-msg">{{error_message.zip}}</div>
                                                        </div>
                                                    </div>
                                                </label>

                                            </div>
                                        </div>
                                        <span v-if="address.id" style="float: right; margin-top: 10px; padding: 5px">
                                            <a @click="updateAddress(address.id)" class="btn">Update</a>
                                        </span>
                                        <span v-else style="float: right; margin-top: 10px; padding: 5px">
                                            <a @click="saveAddress()" class="btn">Save</a>
                                        </span>
                                        <span style="float: right; margin-top: 10px; padding: 5px">
                                            <a @click="cancelAddress()" class="btn">Cancel</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div>
                                <span>Membership
                                    <i class="icon-down-open"></i>
                                </span>
                                <div class="tt-tabs__content">
                                    <div>
                                        <div class="row" style="text-align: right;">
                                            <div class="col-md-12">
                                                <a href="/membership/plans" class="btn">{{(current_subscription == null) ? 'Select plan': 'Change membership'}}</a>
                                            </div>
                                        </div>
                                        <div class="row" v-if="current_subscription && !showChangeMembership">
                                            <div class="col-md-12" v-if="(current_subscription && current_subscription['next_plan_id'] > 0)" style="margin-bottom:15px;">
                                                <div>
                                                    <h4 class="custom-title">New Plan</h4>              
                                                </div>
                                                <div class="row" style="margin-top:15px;">
                                                    <div class="col-md-4">
                                                        <p ><strong>Plan: </strong><span class="text-uppercase">{{current_subscription.next_plan['title']}}</span>/{{interval[current_subscription.next_plan['interval']]}}</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <p ><strong>Next billing date: </strong>{{moment(current_subscription['next_payment_date']).format("MMM Do, YYYY")}}</p>
                                                        <p v-if="current_subscription.next_plan['interval'] == 'month'"><strong>Next charge: </strong>
                                                            <span >${{(current_subscription.next_plan['price']*1).toFixed(2)}}</span>
                                                        </p>
                                                        <p v-if="current_subscription.next_plan['interval'] == 'year'"><strong>Next charge: </strong>
                                                            <span >${{(current_subscription.next_plan['price']*12).toFixed(2)}}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12" v-if="(current_subscription && current_subscription['current_plan_id'] > 0)" style="margin-bottom:15px;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div style="font-size: 16px">
                                                            <b>Your current membership</b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:15px;">
                                                    <div class="col-md-4">
                                                        <p ><strong>Plan: </strong><span class="text-uppercase">{{current_subscription.plan['title']}}</span>/{{interval[current_subscription.plan['interval']]}}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p v-if="current_subscription['last_payment_date'] != null"><strong>Last billing date: </strong>
                                                            {{moment(current_subscription['last_payment_date']).format("MMM Do, YYYY")}}
                                                        </p>
                                                        <p ><strong>Last charge: </strong>${{current_subscription['last_payment']}}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p ><strong>Next billing date: </strong>{{moment(current_subscription['next_payment_date']).format("MMM Do, YYYY")}}</p>
                                                        <p v-if="current_subscription.plan['interval'] == 'month'"><strong>Next charge: </strong>
                                                            <span >${{(current_subscription.plan['price']*1).toFixed(2)}}</span>
                                                        </p>
                                                        <p v-if="current_subscription.plan['interval'] == 'year'"><strong>Next charge: </strong>
                                                            <span >${{(current_subscription.plan['price']*12).toFixed(2)}}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="!current_subscription">
                                            <div style="font-size: 16px">
                                                Currently you don't have a membership
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubscriptionServices from '../services/SubscriptionServices.js'
    import AccountServices from '../services/AccountServices.js';
    import Paginator from './FrontPaginator.vue'
    import Helpers from '../../../../../misc/helpers.js';
    import misc from '../../../../../misc/payments';
    import CardProcessorComponent from '../../../../../components/CardProcessorComponent.vue'
    import PlansComponent from './PlansComponent.vue'
    export default {
        components: {
            'order-paginator': Paginator,
            'card-processor-component': CardProcessorComponent,
            'plans-component': PlansComponent
        },
        mixins: [AccountServices, Helpers,SubscriptionServices],
        props: ['account', 'orders', 'card_processor', 'client_key', 'api_id', 'description'],
        data() {
            return {
                moment:moment,
                window: window,
                current_account: {},
                current_orders: [],
                current_addresses: [],
                current_payment_methods: [],
                order_details: false,
                order_details_data: {
                    order_number: '',
                    products: [],
                    subtotal: '',
                    total_tax_amount: '',
                    shipping: '',
                    payment_method: '',
                    total: 0,
                    status: 0,
                    address1: '',
                    address2: '',
                    zip: '',
                    city: '',
                    state: '',
                    country: '',
                    created_at: '',
                    tracking_info: '',
                    discount_amount:0.00
                },
                tracking_info: {
                    shipping_type: '',
                    shipping_service: '',
                    pickup_date: '',
                    tracking_number: '',
                    activities: []
                },
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: '',
                countries: [],
                states: [],
                address: {
                    id: '',
                    address1: '',
                    address2: '',
                    city: '',
                    zip: '',
                    state: '',
                    country: ''
                },
                user_security_info: {
                    old_pass: '',
                    new_pass: '',
                    new_pass_conf: ''
                },
                showAddAddress: false,

                error: {
                    address: false,
                    city: false,
                    zip: false,
                    state: false,
                    country: false,
                    new_pass: false,
                    name: false,
                    lastname: false,
                    email: false
                },
                error_message: {
                    address: '',
                    city: '',
                    zip: '',
                    state: '',
                    country: '',
                    new_pass: '',
                    name: '',
                    lastname: '',
                    email: '',
                },

                showAddCard: false,
                years: [],

                showChangeMembership: false,
                current_subscription:{},
                plans:[],
                interval:{
                    'year':'yr',
                    'month':'mo',
                },
                paymentId:0
            }
        },
        methods: {
            initComponents(){
                this.order_details = false;
                this.showAddCard = false;
                this.showAddAddress = false;
            },
            editPaymentMethod(id){
                this.paymentId = id;
                this.showAddCard = true;
            },
            goTo(link){
                window.location.href = 'my-account#'+link;
                location.reload();
            },
            showOrderDetails(id) {
                for (var i in this.current_orders) {
                    if (this.current_orders[i].id == id) {
                        this.order_details_data.order_number = this.current_orders[i].order_number;
                        this.order_details_data.products = this.current_orders[i].order_items;
                        this.order_details_data.subtotal = this.current_orders[i].total_amount*1 - this.current_orders[i].total_tax_amount*1 - this.current_orders[i].shipping_cost_estimated*1 + this.current_orders[i].discount_amount*1;
                        /* this.order_details_data.= this.current_orders[i]. */
                        /* this.order_details_data.payment_method = this.current_orders[i].payment_method, */
                        this.order_details_data.shipping_cost_estimated = this.current_orders[i].shipping_cost_estimated;
                        this.order_details_data.total = this.current_orders[i].total_amount;
                        this.order_details_data.total_tax_amount = this.current_orders[i].total_tax_amount;
                        this.order_details_data.address1 = this.current_orders[i].shipping_address1;
                        this.order_details_data.address2 = this.current_orders[i].shipping_address2;
                        this.order_details_data.zip = this.current_orders[i].shipping_zip;
                        this.order_details_data.city = this.current_orders[i].shipping_city;
                        this.order_details_data.state = this.current_orders[i].shipping_state;
                        this.order_details_data.country = this.current_orders[i].shipping_country;
                        this.order_details_data.shipping_status = this.current_orders[i].shipping_status;
                        this.order_details_data.shipping_method = this.current_orders[i].shipping_method;
                        this.order_details_data.shipping_tracking = this.current_orders[i].shipping_tracking;
                        this.order_details_data.created_at = this.current_orders[i].created_at;
                        this.order_details_data.status = this.current_orders[i].status;
                        this.order_details_data.discount_amount = this.current_orders[i].discount_amount;

                        if (this.order_details_data.shipping_tracking) {
                            this.resetTrackingInfo();
                            this.getTrackingInfo('ups', this.order_details_data.shipping_tracking);
                        }

                    }
                }
                this.order_details = true;

            },
            hideOrderDetails(id) {
                this.order_details = false
            },
            showNewAddress() {
                $.LoadingOverlay("show");
                this.getCountriesCall(this.getCountriesCallback);
            },
            getStates() {
                $.LoadingOverlay("show");
                this.getStatesCall(this.getStatesCallback);
            },
            getCountriesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.countries = data;
                    this.showAddAddress = true;
                    this.$nextTick(function(){
                        $("#select2-countries").val('US').trigger('change');
                    })
                }
            },
            getStatesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.states = data;
                }
            },
            saveAddress() {
                if (this.validateAddress()) {
                    this.addAddressCall(this.address, this.addAddressCallback)
                }
            },
            addAddressCallback(code, data) {
                if (code == '200') {
                    this.current_addresses.push(data);
                    this.cancelAddress();
                }
                else if (code == '001') {
                    swal("Error!", data[0], "error");
                }
                else if (code == '002') {
                    swal("Error!", response.errors[0], "error");
                }
            },
            updateAddress(id) {
                if (this.validateAddress()) {
                    $.LoadingOverlay("show");
                    this.updateAddressCall(this.address, id, this.updateAddressCallback)
                }
            },
            updateAddressCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    for (var i in this.current_addresses) {
                        if (this.current_addresses[i].id == data) {
                            this.current_addresses[i].address1 = this.address.address1;
                            this.current_addresses[i].address2 = this.address.address2;
                            this.current_addresses[i].city = this.address.city;
                            this.current_addresses[i].zip = this.address.zip;
                            this.current_addresses[i].state = this.address.state;
                            this.current_addresses[i].country = this.address.country;
                        }
                    }
                    this.cancelAddress();
                }
                else if (code == '001') {
                    swal("Error!", data[0], "error");
                }
                else if (code == '002') {
                    swal("Error!", response.errors[0], "error");
                }
            },
            validateAddress() {
                if (this.address.address1 != '' && this.address.city != ''
                    && this.address.zip != '' && this.address.state != ''
                    && this.address.country != '') {
                    return true;
                } else {
                    if (this.address.address1 == '') {
                        this.error.address = true;
                        this.error_message.address = 'Address is required';
                    }
                    if (this.address.city == '') {
                        this.error.city = true;
                        this.error_message.city = 'City is required';
                    }
                    if (this.address.zip == '') {
                        this.error.zip = true;
                        this.error_message.zip = 'Zip code is required';
                    }
                    if (this.address.state == '') {
                        if (this.address.country == 'US') {
                            $("#select2-states-my-account").select2({
                                containerCssClass: "select2-error"
                            });
                        } else {
                            this.error.state = true;
                        }
                        this.error_message.state = 'State/province is required';

                    }
                    if (this.address.country == '') {
                        $("#select2-countries").select2({
                            containerCssClass: "select2-error",
                            matcher: this.matchCustom
                        });
                        this.error.country = true;
                        this.error_message.country = 'Country is required';
                    }
                }
            },
            setDefaultAddress(address_1) {
                $.LoadingOverlay("show");
                this.setDefaultAddressCall(address_1, this.setDefaultAddressCallback)
            },
            setDefaultAddressCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    for (var i in this.current_addresses) {
                        if (this.current_addresses[i].id == data) {
                            this.current_addresses[i].default = true;
                        } else {
                            this.current_addresses[i].default = false;
                        }
                    }
                }
                else if (code == '001') {
                    swal("Error!", data.errors[0], "error");
                }
                else if (code == '002') {
                    swal("Error!", response.errors[0], "error");
                }
            },
            removeAddress(id) {
                $.LoadingOverlay("show");
                this.removeAddressCall(id, this.removeAddressCallback)
            },
            removeAddressCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    for (var i in this.current_addresses) {
                        if (this.current_addresses[i].id == data) {
                            this.current_addresses.splice(i, 1);
                        }
                    }
                }
                else if (code == '001') {
                    swal("Error!", data.errors[0], "error");
                }
                else if (code == '002') {
                    swal("Error!", response.errors[0], "error");
                }
            },
            editAddress(address) {
                this.address.id = address.id;
                this.address.address1 = address.address1;
                this.address.address2 = address.address2;
                this.address.city = address.city;
                this.address.zip = address.zip;
                this.address.state = address.state;
                this.address.country = address.country;
                this.showNewAddress();
            },
            changePassword() {
                $.LoadingOverlay("show");
                var data = {
                    old_pass: this.user_security_info.old_pass,
                    new_pass: this.user_security_info.new_pass,
                    new_pass_conf: this.user_security_info.new_pass_conf
                }
                this.changePasswordCall(data, this.changePasswordCallback)
            },
            changePasswordCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    swal({ title: "Password changed successfully", text: data.message, timer: 3000, icon: "success" });
                    this.resetUserSecurityInfo();
                }
                else if (code == '001') {
                    swal({ title: "Error", text: data[0], timer: 5000, icon: "error" });
                    this.resetUserSecurityInfo();
                }
                else if (code == '002') {
                    if (data.errors.old_pass) {
                        this.error.old_pass = true;
                        this.error_message.old_pass = 'Current password is required';
                    }
                    if (data.errors.new_pass) {
                        this.error.new_pass = true;
                        this.error_message.new_pass = 'New password is required';
                    }
                    if (data.errors.new_pass_conf) {
                        this.error.new_pass_conf = true;
                        this.error_message.new_pass_conf = 'New password confirmation is required';
                    }
                    this.resetUserSecurityInfo();
                }
            },
            resetUserSecurityInfo() {
                this.user_security_info.old_pass = '';
                this.user_security_info.new_pass = '';
                this.user_security_info.new_pass_conf = '';
            },
            getOrders() {
                $.LoadingOverlay("show");
                axios.get(`api/orders/user-orders?page=${this.paginator.current_page}`)
                    .then((response) => {
                        $.LoadingOverlay("hide");
                        this.paginator = response.data;
                        this.current_orders = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
            showAddPaymentmethod() {
                this.showAddCard = !this.showAddCard;
            },
            addPaymentMethodHandle(data) {
                if(data != false){
                    if(this.paymentId > 0){
                        for(var i in this.current_payment_methods){
                            if(this.current_payment_methods[i].id == this.paymentId){
                                this.current_payment_methods.splice(i, 1, data);
                            }
                        }
                    }else{
                        this.current_payment_methods.push(data);
                    }
                }
                
                this.showAddCard = false;
                this.paymentId = 0;
            },
            removePaymentMethod(id) {
                swal({
                    title: "Remove payment method",
                    text: "Even if you remove this payment method, any pending transaction will be completed.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        this.removePaymentMethodCall(id, this.removePaymentMethodCallback);
                    }
                });

            },
            removePaymentMethodCallback(code, message, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    toastr.success(message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                    if (this.current_payment_methods.length > 0) {
                        for (var i in this.current_payment_methods) {
                            if (this.current_payment_methods[i].id == data) {
                                this.current_payment_methods.splice(i, 1);
                            }
                        }
                    }
                }
                else if (code == '001') {
                    toastr.error(data, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                }
            },
            cancelAddress() {
                this.address.address1 = '';
                this.address.address2 = '';
                this.address.city = '';
                this.address.zip = '';
                this.address.state = '';
                this.address.country = '';

                this.error.address = false;
                this.error.city = false;
                this.error.zip = false;
                this.error.state = false;
                this.error.country = false;

                this.showAddAddress = false;
            },
            update() {
                if (this.validateProfile()) {
                    $.LoadingOverlay("show");
                    this.updateProfile(this.current_account, this.updateProfileCallback);
                }
            },
            updateProfileCallback(code, response, errors) {
                if (code == 200) {
                    swal('Success', 'Profile has been updated successfully', 'success');
                } else {
                    if (this.isAssoc(errors) > 0) {
                        for (let field in errors) {
                            this.error_message[field] = errors[field][0];
                            this.error[field] = true;
                        }
                    } else {
                        swal('Error', errors[0], 'error');
                    }
                }
                $.LoadingOverlay("hide");
            },
            validateProfile() {
                if (this.current_account.name != '' && this.current_account.lastname != ''
                    && this.current_account.email) {
                    return true;
                } else {
                    if (this.current_account.name == '') {
                        this.error.name = true;
                        this.error_message.name = 'Name is required';
                    }
                    if (this.current_account.lastname == '') {
                        this.error.lastname = true;
                        this.error_message.lastname = 'Lastname is required';
                    }
                    if (this.current_account.email == '') {
                        this.error.email = true;
                        this.error_message.email = 'Email is required';
                    }
                }
            },
            resetTrackingInfo() {
                this.tracking_info.tracking_number = '';
                this.tracking_info.shipment_service = '';
                this.tracking_info.shipment_type = '';
                this.tracking_info.activities = [];
                this.tracking_info.status = '';
            },
            getTrackingInfo(carrier, tracking_number) {
                if (tracking_number) {
                    $.LoadingOverlay("show");
                    this.getTrackingInfoCall(carrier, tracking_number, this.getTrackingInfoCallBack)
                }
            },
            getTrackingInfoCallBack(code, msg, data) {
                $.LoadingOverlay("hide");
                this.tracking_info.tracking_number = data.tracking_number;
                this.tracking_info.shipping_type = data.shipment_type;
                this.tracking_info.shipping_service = data.shipment_service;
                if (data.pickup_date) {
                    this.tracking_info.pickup_date = data.pickup_date;
                }
                if (data.activities.length > 0) {
                    for (var i in data.activities) {
                        var activity =
                        {
                            date: data.activities[i].Date,
                            time: data.activities[i].Time,
                            status: data.activities[i].Status.Description,
                        }
                        if (data.activities[i].ActivityLocation) {
                            activity.location_city = data.activities[i].ActivityLocation.Address.City,
                                activity.location_state = data.activities[i].ActivityLocation.Address.StateProvinceCode,
                                activity.location_country = data.activities[i].ActivityLocation.Address.CountryCode,
                                activity.location_zip = data.activities[i].ActivityLocation.Address.PostalCode
                        }

                        this.tracking_info.activities.push(activity);
                    }

                    this.tracking_info.activities.sort(function (a, b) { return b.date - a.date });
                }
            },
            getSubscriptionCallback(response){
                if(response.status > 0){
                    this.current_subscription = response.data.subscription;
                    this.plans = response.data.plans;
                }else{
                    swal('Error', response.errors[0], 'error');
                }
            },
            showPlans() {
                this.showChangeMembership = !this.showChangeMembership;
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
        watch: {
            'address.address1'(val) {
                if (val != '') {
                    this.error.address = false;
                    this.error_message.address = '';
                }
            },
            'address.city'(val) {
                if (val != '') {
                    this.error.city = false;
                    this.error_message.city = '';
                }
            },
            'address.zip'(val) {
                if (val != '') {
                    this.error.zip = false;
                    this.error_message.zip = '';
                }
            },
            'address.state'(val) {
                if (val != '') {
                    this.error.state = false;
                    this.error_message.state = '';
                }
            },
            'address.country'(val) {
                if (val != '') {
                    this.error.country = false;
                    this.error_message.country = '';

                    if (val == 'US') {
                        this.getStates();
                    }
                    $(".select2-selection").removeClass("select2-error");
                }
            },
            'user_security_info.old_pass'(val) {
                if (val != '') {
                    this.error.old_pass = false;
                    this.error_message.old_pass = '';
                }
            },
            'user_security_info.new_pass'(val) {
                if (val != '') {
                    this.error.new_pass = false;
                    this.error_message.new_pass = '';
                }
            },
            'user_security_info.new_pass_conf'(val) {
                if (val != '') {
                    this.error.new_pass_conf = false;
                    this.error_message.new_pass_conf = '';
                }
            },
            'current_account.name'(val) {
                if (val != '') {
                    this.error.name = false;
                    this.error_message.name = '';
                }
            },
            'current_account.lastname'(val) {
                if (val != '') {
                    this.error.lastname = false;
                    this.error_message.lastname = '';
                }
            },
            'current_account.email'(val) {
                if (val != '') {
                    this.error.email = false;
                    this.error_message.email = '';
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
            if (this.account) {
                this.current_account = this.account;
                if (this.orders) {
                    this.paginator = this.orders;
                    this.current_orders = this.orders.data;
                    this.offset = (this.paginator.current_page - 1) * this.paginator.per_page + 1;
                }
                if (this.current_account.customer_profile) {
                    if (this.current_account.customer_profile.payment_profiles.length > 0) {
                        this.current_payment_methods = this.current_account.customer_profile.payment_profiles;
                    }
                }
                if (this.current_account.addresses.length > 0) {
                    this.current_addresses = this.current_account.addresses;
                }
            }
            var date = new Date,
                years = [],
                year = date.getFullYear();

            for (var i = year; i < year + 20; i++) {
                this.years.push(i);
            }
            this.getSubscription(this.getSubscriptionCallback);
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $("#select2-countries").select2({
                    matcher: self.matchCustom
                });
                $("#select2-states-my-account").select2({
                    matcher: self.matchCustom
                });
            });

            $('#select2-countries').on('change', function (e) {
                self.address.country = $('#select2-countries').select2('data')[0].id;
                // console.log($('#select2-countries').val());
                if (self.address.country == 'US') {
                    self.getStates();
                }
                if (self.address.country != '') {
                    $(".select2-selection").removeClass("select2-error");
                }
            });

            $('#select2-states-my-account').on('change', function (e) {
                self.address.state = $('#select2-states-my-account').select2('data')[0].id;
                if (self.address.state != '') {
                    $(".select2-selection").removeClass("select2-error");
                }
            });
            $('#select2-states-my-account').on('select2:open', function (e) {
                setTimeout(function(){
                    $('.select2-container input').focus();
                },50);
            });
        }
    }
</script>
<style>
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

    .ttg-mt--30,
    .ttg-mb--30 {
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }

    .swal-text {
        text-align: center;
        color: #61534e;
        font-size: 13px;
    }

    .swal-title {
        text-align: center;
        color: #61534e;
        font-size: 18px;
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
        font-size: 12px
    }

    .tt-input {
        margin-bottom: 0px !important;
    }

    input[type='text'].form-control,
    input[type='email'].form-control,
    input[type='search'].form-control,
    input[type='password'].form-control,
    input[type='tel'].form-control,
    textarea.form-control,
    select.form-control {
        margin-bottom: 10px;
    }

    ::placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: rgb(153, 150, 150) !important;
        opacity: 1;
        /* Firefox */
    }

    :-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: rgb(153, 150, 150) !important;
    }

    ::-ms-input-placeholder {
        /* Microsoft Edge */
        color: rgb(153, 150, 150) !important;
    }

    .form-group {
        margin-bottom: 0px !important;
    }

    .delivered {
        color: green;
    }

    .pending {
        color: orangered;
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

    @media only screen and (min-width: 1025px){
        a:hover {
            color: #13919b!important;
        }
    }

    @media (max-width: 480px){
        .container {
            width: 750px;
            max-width: 100%;
        }
    }
</style>