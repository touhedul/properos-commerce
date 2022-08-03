<template>
    <div>
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <section id="icon-tabs" style="width: 100%">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <template v-if="!order.id || order.id == 0">
                                        <h4 class="card-title">New order</h4>
                                        <div style="padding: 2px">
                                            <span id="origin" style="font-size: 12px; cursor:pointer;">Origin:
                                                <b v-if="!edit_origin">{{order.origin | capitalize}}</b>
                                                <i  v-if="!edit_origin" @click="edit_origin = true" class="fa fa-pencil dropdown origin-class" style="font-size:13px; align-self:center;cursor:pointer;"></i>
                                                <div  v-if="edit_origin" class="position-relative has-icon-right" style="align-self:center; width:200px;display:inline-block;">
                                                    <input class="form-control custom-form-control" style="margin-top:10px;display:inline;padding-left:10px;" v-model="prev_origin" autofocus>
                                                    <div class="form-control-position" style="width:3.5rem;margin-top:-15px;">
                                                        <i @click="changeOrigin()" class="fa fa-check" style="font-size:18px;color:#16D39A;cursor:pointer;"></i>
                                                        <i @click="edit_origin = false; prev_origin = order.origin" class="fa fa-close" style="font-size:18px;color:#FF7588;cursor:pointer;"></i>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="store" style="font-size: 12px; cursor:pointer;">Store location:
                                                <b v-if="!edit_location">{{store.name | capitalize}}</b>
                                                <i  v-if="!edit_location" @click="edit_location = true" class="fa fa-pencil dropdown origin-class" style="font-size:13px; align-self:center;cursor:pointer;"></i>
                                                <div  v-show="edit_location" class="position-relative has-icon-right location-class" style="align-self:center; width:200px;display:inline-block;">
                                                    <select id="select2-location" required class="select2-placeholder form-control" data-placeholder="Select location..."></select>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="pay_status" style="font-size: 12px; cursor:pointer;">Payment status:
                                                <b>{{order_status | capitalize}}</b>
                                                <i class="fa fa-pencil dropdown edit-class" style="font-size:13px; align-self:center;cursor:pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                <div class="dropdown-menu">
                                                    <button v-if="order_status != 'pending'" @click="changePaymentStatus('pending')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/pending.png" style="margin-right:5px;"> Pending</button>
                                                    <button v-if="order_status != 'paid'" @click="changePaymentStatus('paid')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/paid.png" style="margin-right:5px;"> Paid</button>
                                                    <button v-if="order_status != 'refunded'" @click="changePaymentStatus('refunded')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/refunded.png" style="margin-right:5px;"> Refunded</button>
                                                    <button v-if="order_status != 'cancelled'" @click="changePaymentStatus('cancelled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/cancel.png" style="margin-right:5px;"> Cancelled</button>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="status" style="font-size: 12px">Order status:
                                                <b>{{order.shipping_status | capitalize}}</b>
                                                <i class="fa fa-pencil dropdown status-class" style="font-size:13px; align-self:center;cursor:pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                 <div class="dropdown-menu">
                                                    <button v-if="order.shipping_status != 'pending'" @click="changeOrderStatus('pending')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/pending.png" style="margin-right:5px;"> Pending</button>
                                                    <button v-if="order.shipping_status != 'fullfiling'" @click="changeOrderStatus('fullfiling')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/fullfiling.png" style="margin-right:5px;"> Fullfiling</button>
                                                    <button v-if="order.shipping_status != 'fulfilled'" @click="changeOrderStatus('fulfilled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/fulfilled.png" style="margin-right:5px;"> Fulfilled</button>
                                                    <button v-if="order.shipping_status != 'shipped'" @click="changeOrderStatus('shipped')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/shipped.png" style="margin-right:5px;"> Shipped</button>
                                                    <button v-if="order.shipping_status != 'delivered'" @click="changeOrderStatus('delivered')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/delivered.png" style="margin-right:5px;"> Delivered</button>
                                                    <button v-if="order.shipping_status != 'returned'" @click="changeOrderStatus('returned')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/returned.png" style="margin-right:5px;"> Returned</button>
                                                    <button v-if="order.shipping_status != 'cancelled'" @click="changeOrderStatus('cancelled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/cancel.png" style="margin-right:5px;"> Cancelled</button>
                                                </div>
                                            </span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div style="padding: 2px">
                                            <span id="order_number" style="font-size: 12px">Order number:
                                                <b v-if="!edit_order_number">{{order.order_number}}</b>
                                                <i  v-if="!edit_order_number" @click="edit_order_number = true" class="fa fa-pencil dropdown order-number-class" style="font-size:13px; align-self:center;cursor:pointer;"></i>
                                                <div  v-if="edit_order_number" class="position-relative has-icon-right" style="align-self:center; width:200px;display:inline-block;">
                                                    <input class="form-control custom-form-control" style="margin-top:10px;display:inline;padding-left:10px;" v-model="prev_order_number" autofocus>
                                                    <div class="form-control-position" style="width:3.5rem;margin-top:-15px;">
                                                        <i @click="changeOrderNumber()" class="fa fa-check" style="font-size:18px;color:#16D39A;cursor:pointer;"></i>
                                                        <i @click="edit_order_number = false; prev_order_number = order.order_number" class="fa fa-close" style="font-size:18px;color:#FF7588;cursor:pointer;"></i>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="origin" style="font-size: 12px; cursor:pointer;">Origin:
                                                <b v-if="!edit_origin">{{order.origin | capitalize}}</b>
                                                <i  v-if="!edit_origin" @click="edit_origin = true" class="fa fa-pencil dropdown origin-class" style="font-size:13px; align-self:center;cursor:pointer;"></i>
                                                <div  v-if="edit_origin" class="position-relative has-icon-right" style="align-self:center; width:200px;display:inline-block;">
                                                    <input class="form-control custom-form-control" style="margin-top:10px;display:inline;padding-left:10px;" v-model="prev_origin" autofocus>
                                                    <div class="form-control-position" style="width:3.5rem;margin-top:-15px;">
                                                        <i @click="changeOrigin()" class="fa fa-check" style="font-size:18px;color:#16D39A;cursor:pointer;"></i>
                                                        <i @click="edit_origin = false; prev_origin = order.origin" class="fa fa-close" style="font-size:18px;color:#FF7588;cursor:pointer;"></i>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="store" style="font-size: 12px; cursor:pointer;">Store location:
                                                <b v-if="!edit_location">{{store.name | capitalize}}</b>
                                                <i  v-if="!edit_location" @click="edit_location = true" class="fa fa-pencil dropdown origin-class" style="font-size:13px; align-self:center;cursor:pointer;"></i>
                                                <div  v-show="edit_location" class="position-relative has-icon-right location-class" style="align-self:center; width:200px;display:inline-block;">
                                                    <select id="select2-location" required class="select2-placeholder form-control" data-placeholder="Select location..."></select>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="pay_status" style="font-size: 12px; cursor:pointer;">Payment status:
                                                <b>{{order_status | capitalize}}</b>
                                                <i class="fa fa-pencil dropdown edit-class" style="font-size:13px; align-self:center;cursor:pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                <div class="dropdown-menu">
                                                    <button v-if="order_status != 'pending'" @click="changePaymentStatus('pending')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/pending.png" style="margin-right:5px;"> Pending</button>
                                                    <button v-if="order_status != 'paid'" @click="changePaymentStatus('paid')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/paid.png" style="margin-right:5px;"> Paid</button>
                                                    <button v-if="order_status != 'refunded'" @click="changePaymentStatus('refunded')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/refunded.png" style="margin-right:5px;"> Refunded</button>
                                                    <button v-if="order_status != 'cancelled'" @click="changePaymentStatus('cancelled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/cancel.png" style="margin-right:5px;"> Cancelled</button>
                                                </div>
                                            </span>
                                        </div>
                                        <div style="padding: 2px">
                                            <span id="status" style="font-size: 12px">Order status:
                                                <b>{{order.shipping_status | capitalize}}</b>
                                                <i class="fa fa-pencil dropdown status-class" style="font-size:13px; align-self:center;cursor:pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                 <div class="dropdown-menu">
                                                    <button v-if="order.shipping_status != 'pending'" @click="changeOrderStatus('pending')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/pending.png" style="margin-right:5px;"> Pending</button>
                                                    <button v-if="order.shipping_status != 'fullfiling'" @click="changeOrderStatus('fullfiling')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/fullfiling.png" style="margin-right:5px;"> Fullfiling</button>
                                                    <button v-if="order.shipping_status != 'fulfilled'" @click="changeOrderStatus('fulfilled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/fulfilled.png" style="margin-right:5px;"> Fulfilled</button>
                                                    <button v-if="order.shipping_status != 'shipped'" @click="changeOrderStatus('shipped')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/shipped.png" style="margin-right:5px;"> Shipped</button>
                                                    <button v-if="order.shipping_status != 'delivered'" @click="changeOrderStatus('delivered')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/delivered.png" style="margin-right:5px;"> Delivered</button>
                                                    <button v-if="order.shipping_status != 'returned'" @click="changeOrderStatus('returned')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/returned.png" style="margin-right:5px;"> Returned</button>
                                                    <button v-if="order.shipping_status != 'cancelled'" @click="changeOrderStatus('cancelled')" class="dropdown-item" type="button"><img class="icons-custom" src="/images/icons/cancel.png" style="margin-right:5px;"> Cancelled</button>
                                                </div>
                                            </span>
                                        </div>
                                    </template>
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
                                        <div class="form-actions" style="margin-top:25px;text-align:right;">
                                            <a v-if="order.id" href="#duplicate" @click="duplicateOrder()" class="btn btn-primary" style="padding: 0.75rem 1rem;" title="Packing slip">
                                                <span class="d-none d-md-block"><i class="fa fa-copy"></i> Duplicate Order</span>
                                                <span class="d-md-none"><i class="fa fa-copy"></i> </span>
                                                
                                            </a>
                                            <a v-if="order.id" :href="app_url+'/orders/packing-slip/'+order.token" class="btn btn-primary" style="padding: 0.75rem 1rem;" title="Packing slip">
                                                <span class="d-none d-md-block"><i class="fa fa-cubes"></i> Packing Slip</span>
                                                <span class="d-md-none"><i class="fa fa-cubes"></i></span>
                                                
                                            </a>
                                            <a v-if="order.id" :href="app_url+'/orders/invoice/'+order.token" class="btn btn-primary" style="padding: 0.75rem 1rem;" title="Invoice">
                                                <span class="d-none d-md-block"><i class="fa fa-file-o"></i> Invoice</span>
                                                <span class="d-md-none"><i class="fa fa-file-o"></i> </span>
                                            </a>
                                            <button v-if="order.id > 0" @click="updateOrder()" type="button" class="btn btn-primary">
                                                <span class="d-none d-md-block"><i class="fa fa-save"></i> Update</span>
                                                <span class="d-md-none"><i class="fa fa-save"></i> </span>
                                            </button>
                                        </div>
                                        <div v-if="order.id && order.status == 'pending'" class="form-actions" style="margin-top:25px;text-align:right; font-size:12px;">
                                            <a  :href="'/admin/orders/'+currentUserInfo.id+'/'+order.id+'/configure-payment'" style="color:#404E67;"> 
                                                Make a payment
                                            </a><br>
                                        </div>
                                        <div v-if="order.id && order_status != 'pending'" class="form-actions" style="margin-top:10px;text-align:right; font-size:12px;">
                                            <a :href="'/admin/orders/'+order.id+'/payment-history'" style="color:#404E67;"> 
                                                Payment history
                                            </a>
                                        </div>
                                        <!-- <div v-if="editable_order.id" class="form-actions" style="margin-top:10px;text-align:right; font-size:12px;">
                                            <a :href="'/admin/orders/'+editable_order.id+'/payment-history'" style="color:#404E67;"> 
                                                Duplicate Order
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="card-body" style="width: 100%">
                                            <div class="card-text">
                                                <p></p>
                                            </div>
                                            <form class="form form-horizontal">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="form-section">
                                                                <i class="ft-user"></i> Customer Info</h4>
                                                            <div v-if="selectCustomer" class="form-group row">
                                                                <label class="col-md-12 label-control" for="projectinput1" style="text-align:left!important;">Search customer</label>
                                                                <div class="col-md-12">
                                                                    <div class="input-group" v-bind:class="{'input-error-select' : error.customer}">
                                                                        <select id="select2-customer" required class="select2-placeholder form-control" data-placeholder="Select customer..." style="width: 100%">
                                                                            <!-- <option v-for="(customer,key) in current_customers" :key="key" :value="customer.id">{{customer.name}} {{customer.lastname}}</option> -->
                                                                        </select>
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newCustomer">
                                                                                <i class="ft-user"></i>
                                                                                <span class="d-none d-md-inline-block">Add customer</span>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                    <span v-if="error.customer" class="message-error">{{error_message.customer}}</span>
                                                                </div>
                                                            </div>
                                                            <div v-if="currentUserInfo.avatar || currentUserInfo.email || currentUserInfo.phone" class="form-group row">
                                                                <div v-if="currentUserInfo.avatar" class="col-md-3 offset-md-2" style="padding: 5px">
                                                                    <span class="avatar avatar-online" style="width: 80px; height: auto;">
                                                                        <img :src="currentUserInfo.avatar">
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div v-if="customer_name" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Full name</label>
                                                                            <input type="text" v-model="customer_name" class="form-control" id="name" disabled>
                                                                        </div>
                                                                        <div v-if="customer_email" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Email</label>
                                                                            <input type="email" v-model="customer_email" class="form-control" id="email" disabled>
                                                                        </div>
                                                                        <div v-if="currentUserInfo.phone" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Phone</label>
                                                                            <input type="text" v-model="currentUserInfo.phone" class="form-control" id="phone" disabled>
                                                                        </div>
                                                                        <div v-if="customer_company" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Company</label>
                                                                            <input type="text" v-model="customer_company" class="form-control" id="customer_company" disabled>
                                                                        </div>
                                                                        <div v-if="order.status != ''" class="col-md-12" style="text-align:right; padding-top: 15px">
                                                                            <button class="btn btn-secondary" type="button" style="display:inline-block;" data-toggle="modal" data-target="#sendQuote">
                                                                                <!-- <i class="fa fa-send"></i> -->
                                                                                <span class="d-sm-inline">Send Quote</span>
                                                                            </button>
                                                                            <button class="btn btn-secondary" type="button" style="display:inline-block;" data-toggle="modal" data-target="#sendInvoice">
                                                                                <i class="fa fa-send"></i>
                                                                                <span class="d-none d-xl-inline">Send order invoice</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-else-if="customer_name != '' || customer_email != ''" class="form-group row">
                                                                <div class="col-md-3 offset-md-2" style="padding: 5px">
                                                                    <span class="avatar avatar-online" style="width: 80px; height: auto;">
                                                                        <img src="/images/avatar-placeholder.png">
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div v-if="customer_name != ''" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Name</label>
                                                                            <input type="text" v-model="customer_name" class="form-control" id="customer_name" disabled>
                                                                        </div>
                                                                        <div v-if="customer_email" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Email</label>
                                                                            <input type="email" v-model="customer_email" class="form-control" id="customer_email" disabled>
                                                                        </div>
                                                                        <div v-if="customer_phone" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Phone</label>
                                                                            <input type="text" v-model="customer_phone" class="form-control" id="customer_phone" disabled>
                                                                        </div>
                                                                        <div v-if="customer_company" class="col-md-12">
                                                                            <label class="label-control" for="projectinput2" style="font-size: 12px">Company</label>
                                                                            <input type="text" v-model="customer_company" class="form-control" id="customer_company" disabled>
                                                                        </div>
                                                                        <div v-if="order.status != ''" @click="sendInvoiceEmail(order.id)" class="col-md-12" style="text-align:right; padding-top: 15px">
                                                                            <button class="btn btn-secondary" type="button" style="display:inline-block;">
                                                                                <i class="fa fa-send"></i>
                                                                                <span class="d-none d-xl-inline">Send order invoice</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div v-if="order.phone" class="col-md-4 offset-md-3">
                                                                    <label class="label-control" for="projectinput2">Phone number</label>
                                                                    <input type="tel" v-model="order.phone" class="form-control" id="phoneNumber2" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="form-section">
                                                            <i class="fa fa-truck"></i>
                                                            Shipping address & tracking</h4>
                                                            <div v-if="order.shipping_addresses.length > 0" class="form-group row">
                                                                <div class="col-md-12" style="padding-left:0px;">
                                                                    <ul style="padding-left:20px;">
                                                                        <li v-for="(shipping_addres, index) in order.shipping_addresses" class="list-group-item" :key="index">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label>
                                                                                        {{shipping_addres.shipping_address }} {{shipping_addres.shipping_address_cont }} {{shipping_addres.shipping_city }} {{shipping_addres.shipping_state}}
                                                                                        {{shipping_addres.shipping_zip }}
                                                                                        {{shipping_addres.shipping_country}}
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12" style="text-align:right;">
                                                                                            <button @click="loadShippingAddres(index)" :disabled="disable_shipping_address"  data-toggle="modal"
                                                                                                data-target="#shippingAddress"
                                                                                                class="btn btn-warning" type="button"
                                                                                                style="display:inline-block;">
                                                                                                <i class="fa fa-pencil"></i>
                                                                                                <span class="d-none d-xl-inline">Edit</span>
                                                                                            </button>
                                                                                            <button @click="removeShippingAddres(index)" class="btn btn-danger" type="button" style="display:inline-block;" :disabled="disable_shipping_address" >
                                                                                                <i class="fa fa-trash"></i>
                                                                                                <span class="d-none d-xl-inline">Remove</span>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <button v-if="order.shipping_addresses.length < 1" type="button" class="btn btn-secondary btn-min-width mr-1 mb-1" data-toggle="modal"
                                                                        data-target="#shippingAddress" style="float: right">
                                                                        <i class="fa fa-truck"></i>
                                                                        Add shipping address
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row" v-if="shipping">
                                                                <div class="col-md-6" style="padding:0px;">
                                                                    <label class="col-md-12 label-control" for="projectinput2" style="text-align:left!important;">Shipping method</label>
                                                                    <div class="input-group col-md-12">
                                                                        <select id="select2-shipping-method" v-model="order.shipping_method_id" :disabled="disable_shipping" class="select2-placeholder form-control"
                                                                            data-placeholder="Select method..." style="width: 100%">
                                                                            <option v-for="(shipping_method, key) in current_shipping_methods" :key="key" :value="shipping_method.id" :title="shipping_method.name">{{shipping_method.label}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <label class="col-md-12 label-control" for="projectinput2" style="text-align:left!important; margin-top: 20px">Shipping service</label>
                                                                    <div class="input-group col-md-12">
                                                                        <select id="select2-shipping-services" v-model="order.shipping_method_code" :disabled="disable_shipping" class="select2-placeholder form-control"
                                                                            data-placeholder="Select service..." style="width: 100%">
                                                                            <option v-for="(shipping_service, key) in current_shipping_services" :key="key" :value="key">{{shipping_service.description}} 
                                                                                <span v-if="shipping_service.code=='03'" style="text-decoration: line-through;">(${{shipping_service.cost}})</span>
                                                                                <span v-else>(${{shipping_service.cost}})</span>
                                                                                <span v-if="key=='03'">(Free)</span>
                                                                                </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6" style="padding:0px;">
                                                                    <label class="col-md-12 label-control" for="projectinput2" style="text-align:left!important;">Tracking number</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" v-model="order.tracking_number" :disabled="disable_shipping" class="form-control" id="trackingNumber">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button v-if="order.tracking_number == ''" @click="getShippingInfo()" type="button" class="btn btn-secondary btn-min-width mr-1 mb-1"
                                                                            :disabled="disable_shipping" style="float: right; margin-top:22px;">
                                                                            <i class="fa fa-plus"></i> Create shipping label
                                                                        </button>
                                                                        <div v-if="order.tracking_number != ''" style="margin-top:22px">
                                                                            <a :href="'/admin/store/order/print-label/' + order.id" target="blank" class="btn btn-primary btn-min-width mr-1 mb-1" style="float: right;">
                                                                                Print shipping label
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" v-if="shipping">
                                                                <div class="col-md-6" style="padding:0px;">
                                                                    <label class="col-md-12 label-control" for="shipping_cost_estimate" style="text-align:left!important;">Shipping estimate</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" v-model="order.shipping_cost_estimated" :disabled="disable_cost_estimated" class="form-control" id="shipping_cost_estimate">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6" style="padding:0px;">
                                                                    <label class="col-md-12 label-control" for="shipping_cost" style="text-align:left!important;">Shipping cost</label>
                                                                    <div class="col-md-12">
                                                                        <input type="text" v-model="order.shipping_cost" :disabled="disable_shipping" class="form-control" id="shipping_cost">
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="col-md-6" style="padding:0px;">
                                                                    <label class="col-md-12 label-control" for="projectinput2" style="text-align:left!important;">Shipping status</label>
                                                                    <div class="input-group col-md-12">
                                                                        <select id="select2-shipping-shipping_status" v-model="order.shipping_status" :disabled="disable_shipping" class="select2-placeholder form-control"
                                                                            data-placeholder="Select shipping_status..." style="width: 100%">
                                                                            <option :value="-1"></option>
                                                                            <option v-for="(shipping_st, index) in shipping_status_collection" :key="index" :value="shipping_st.value">{{shipping_st.label}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div> -->
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <fieldset>
                                                                        <div class="input-group">
                                                                            <div class="skin skin-square">
                                                                                <div class="form-group text-right" style="margin-bottom: 10px; margin-top:15px;">
                                                                                    <input type="checkbox" id="partial">
                                                                                    <label for="partial">Allow partial payments</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div v-show="order.partial">
                                                                            <input type="text" class="form-control" v-model="order.min_payment" placeholder="Minimum amount" />
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-6" v-if="!shipping && order.id>0">
                                                                    <fieldset>
                                                                        <div class="input-group">
                                                                            <div @click="shipping = true" class="col-md-12" style="text-align:right; padding-top: 15px">
                                                                                <button class="btn btn-secondary" type="button" style="display:inline-block;">
                                                                                    <i class="fa fa-address-card"></i>
                                                                                    <span class="d-none d-xl-inline">Add shipping info</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <a v-if="disable_shipping && order.tracking_number == ''" @click="changeShippingInfo()" style="float: right; cursor: pointer">Change shipping info</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="form-section">
                                                                <i class="ft-box"></i> Products</h4>
                                                            <div class="form-group row">
                                                                <div class="col-md-3">
                                                                    <label class="label-control" for="projectinput5">Category</label>
                                                                    <div class="input-group" v-bind:class="{'input-error-select' : error.category}">
                                                                        <select id="select2-category" class="select2-placeholder form-control" :disabled="disable_products" data-placeholder="Select category...">
                                                                            <!-- <option v-for="(category, key) in current_categories" :key="key" :value="category.id">{{category.name}}</option> -->
                                                                        </select>
                                                                    </div>
                                                                    <span v-if="error.category" class="message-error">{{error_message.category}}</span>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="label-control" for="projectinput6">Product</label>
                                                                    <div class="input-group" v-bind:class="{'input-error-select' : error.item}">
                                                                        <select id="select2-product" class="select2-placeholder form-control" :disabled="disable_products" data-placeholder="Select product...">
                                                                            <!-- <option v-for="(product, key) in selectables_products" :key="key" :value="product.id">{{product.name}}</option> -->
                                                                        </select>
                                                                    </div>
                                                                    <span v-if="error.item" class="message-error">{{error_message.item}}</span>
                                                                </div>
                                                                <!--   <div class="col-md-3">
                                                                    <label class="label-control" for="projectinput7">Variant</label>
                                                                    <div class="input-group">
                                                                        <select id="select2-variant" class="select2-placeholder form-control" data-placeholder="Select variant (sku)" disabled>
                                                                            <option v-for="variant in product_variants" :value="variant.id">{{variant.attribute[0]}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div> -->
                                                                <div class="col-md-3">
                                                                    <label class="label-control">Quantity</label>
                                                                    <div class="input-group" v-bind:class="{'input-error-select' : error.qty}" style="width: 50%">
                                                                        <input type="number" min="1" v-model="order_item.qty" class="form-control" id="quantity" :disabled="disable_products" step="0.01">
                                                                    </div>
                                                                    <span v-if="error.qty" class="message-error">{{error_message.qty}}</span>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="label-control">&nbsp;</label>
                                                                    <button v-if="order.id > 0 || order_created == true" @click="addItemOnUpdate()" type="button" class="btn btn-secondary btn-min-width mr-1 mb-1"
                                                                        :disabled="disable_products" style="float: right; margin-top:22px;">
                                                                        <i class="fa fa-plus"></i> Add item
                                                                    </button>
                                                                    <button v-else @click="addItem()" type="button" class="btn btn-secondary btn-min-width mr-1 mb-1" style="float: right; margin-top:22px;">
                                                                        <i class="fa fa-plus"></i> Add item
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div  v-if="(order.customer_id != '' || order.customer_id > 0) && order.order_products.length > 0 && 
                                                                        (order.status == null || order.status =='pending' || order.status == '')" 
                                                                        class="row" style="margin-top:10px; margin-bottom:15px;">
                                                                        <div class="col-md-6" style="padding-right: 29px;">
                                                                            <div class="input-group" v-bind:class="{'input-error-select' : error.coupon}">
                                                                                <input type="text" v-model="discount_code" class="form-control" placeholder="coupon">
                                                                                <span class="input-group-btn">
                                                                                    <button @click="checkDiscount()" class="btn btn-primary" type="button" :disabled="enabled_apply">
                                                                                        <span>Apply</span>
                                                                                    </button>
                                                                                </span>
                                                                            </div>
                                                                            <span v-if="error.coupon" class="message-error">{{error_message.coupon}}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div v-if="order.discount_amount != 0" style="margin-top:10px; margin-bottom:15px;">
                                                                        <div  style="font-size:12px; color: black; font-weight: bold;padding-bottom:10px;">
                                                                            <p style="margin-bottom:0;">Coupon: {{order.discount_code}}</p>
                                                                            <p style="margin-bottom:0;">{{coupon_message}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4" style="text-align: right;">
                                                                    <div v-if="order.order_products.length > 0" style="padding-bottom: 15px; font-size: 18px">
                                                                         <b v-if="order.discount_amount >=0">Items Subtotal:
                                                                            <!-- <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat((order.total_amount-order.total_tax_amount-order.shipping_cost_estimated + order.discount_amount),2)}}</b> -->
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.subtotal*1,2)}}</b>
                                                                         <b v-else>Items Subtotal:    
                                                                            <span v-html="current_currency.symbol" ></span>{{Helpers.numberFormat((order.total_amount-order.total_tax_amount-order.shipping_cost_estimated - order.discount_amount),2)}}</b>
                                                                     </div>
                                                                     <div v-if="order.discount_amount > 0" style="padding-bottom: 5px; font-size: 18px">
                                                                         <b>Discount({{order.discount_code}}):
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.discount_amount,2)}}</b>
                                                                     </div>
                                                                     <div style="padding-bottom: 5px; font-size: 18px">
                                                                         <b>Shipping & Handling:
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.shipping_cost_estimated,2)}}</b>
                                                                     </div>
                                                                     <div style="padding-bottom: 5px; font-size: 18px">
                                                                         <b>Total Before Tax:
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.subtotal*1+order.shipping_cost_estimated*1-order.discount_amount*1,2)}}</b>
                                                                     </div>
                                                                     <div style="padding-bottom: 5px; font-size: 18px">
                                                                         <b>Taxes:
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.total_tax_amount,2)}}</b>
                                                                     </div>
                                                                    <div v-if="order.order_products.length > 0" style="padding-bottom: 15px; font-size: 18px">
                                                                         <b>Total amount:
                                                                            <span v-html="current_currency.symbol"></span>{{Helpers.numberFormat(order.subtotal*1+order.shipping_cost_estimated*1-order.discount_amount*1+order.total_tax_amount*1,2)}}</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div v-if="order.customer_id > 0 && order.shipping_addresses.length > 0 && order.order_products.length > 0 && 
                                                                (order.status == '' || order.status =='pending')" 
                                                                class="row" style="margin-top:10px; margin-bottom:15px;">
                                                                <div class="col-md-5 offset-md-7 col-lg-4 offset-lg-8" style="padding-right: 29px;">
                                                                    <div class="input-group" v-bind:class="{'input-error-select' : error.coupon}">
                                                                        <input type="text" v-model="discount_code" class="form-control" placeholder="coupon">
                                                                        <span class="input-group-btn">
                                                                            <button @click="checkDiscount()" class="btn btn-primary" type="button" :disabled="enabled_apply">
                                                                                <span>Apply</span>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                    <span v-if="error.coupon" class="message-error">{{error_message.coupon}}</span>
                                                                </div>
                                                            </div>
                                                            <div v-if="validate_coupon.length > 0" 
                                                                class="row" style="margin-top:10px; margin-bottom:15px;">
                                                                <div class="col-md-5 offset-md-7 col-lg-4 offset-lg-8" style="padding-right: 29px;">
                                                                    <div  style="font-size:12px; color: black; font-weight: bold;padding-bottom:10px;">
                                                                        <p style="margin-bottom:0;">Coupon: {{applied_coupon}}</p>
                                                                        <p style="margin-bottom:0;">{{validate_coupon}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div v-if="order.order_products.length > 0" style="float: right; padding-bottom: 15px; font-size: 18px">
                                                                        <b>Total amount:
                                                                            <span v-html="current_currency.symbol"></span>{{order.total_amount}}</b>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <ul class="list-group">
                                                                        <li v-for="(order_product, index) in order.order_products" class="list-group-item" :key="index">
                                                                            <div class="row" v-if=" order.order_products[index].id > 0">
                                                                                <div class="col-md-2 text-center">
                                                                                    <span class="">
                                                                                        <a href="#">
                                                                                            <img :src="order_product.thumb_path" style="width: auto; height: 120px" />
                                                                                        </a>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="form-group row">
                                                                                        <label style="width: 100%">Name:</label>
                                                                                        <div style="width: 80%">
                                                                                            <!-- <div style="font-size: 20px">{{order_product.name}}</div> -->
                                                                                            <input type="text" v-model="order_product.name" class="form-control" :disabled="disable_products">
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
                                                                                            <input @input="updateTotalAmount(index)" type="number" min="1" v-model="order.order_products[index].qty" class="form-control" step="0.01"
                                                                                                :disabled="disable_products"
                                                                                                id="quantity" placeholder="qty"
                                                                                                style="width: 100%">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group row">
                                                                                        <label style="width: 100%">Regular price:</label>
                                                                                        <div style="width: 80%">
                                                                                            <input type="text"  @input="updateTotalAmount(index)" v-model="order.order_products[index].price" class="form-control" style="width: 100%" :disabled="disable_products">
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
                                                                                    <button v-if="(order.id > 0 || order_created == true)" type="button" @click="removeItemOnUpdate(index, order_product.id)"
                                                                                        :disabled="disable_products" class="btn btn-danger float-right">
                                                                                        <i class="fa fa-trash"></i>
                                                                                        <span class="d-none d-xl-inline">Remove item</span>
                                                                                    </button>
                                                                                    <button v-else type="button" @click="removeItem(index)" class="btn btn-danger float-right">
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
                                                                        <label>Additional notes for this order</label>
                                                                        <textarea name="notes" v-model="order.notes" id="description" rows="5" class="form-control" placeholder="Type some notes...">
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-6">
                                                            <h4 class="form-section">
                                                                <i class="fa fa-credit-card"></i> Checkout</h4>
                                                            <div class="form-group row">
                                                                <label class="col-md-3 label-control" for="projectinput5">Payment method</label>
                                                                <div class="col-md-6">
                                                                    <div class="input-group" style="margin-bottom: 15px">
                                                                        <select id="select2-payment-method" @change="setPaymentMethod" class="form-control" v-model="order.payment_method_id">
                                                                            <option selected style="color: #999999">Select payment method...</option>
                                                                            <option v-for="payment_method in current_payment_methods" :value="payment_method.id">{{payment_method.label}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <template v-if="payment_flag == 'paypal'">
                                                                        <div class="form-group">
                                                                            <label for="userinput1">Paypal email</label>
                                                                            <div class="input-group">
                                                                                <input type="text" v-model="paypalEmail" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                    <template v-if="payment_flag == 'credit-card'">
                                                                        <div class="form-group">
                                                                            <label for="userinput1">Credit/Debit card information</label>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="userinput1">Card holder</label>
                                                                            <div class="input-group">
                                                                                <input type="text" v-model="cardInfo.card_holder" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="userinput1">Card number</label>
                                                                            <div class="input-group">
                                                                                <input type="text" v-model="cardInfo.card_number" class="form-control" id="cardNumber">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="userinput1">Expiration date</label>
                                                                                        <div class="input-group">
                                                                                            <input type="text" v-model="cardInfo.exp_date" class="form-control" id="cardExpiration">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="userinput1">CVV</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" v-model="cardInfo.cvv" class="form-control" id="cardCvv">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div v-if="payment_flag == 'paypal'">
                                                                        <img src="/images/paypal.png" style="width: 150px; height: auto">
                                                                    </div>
                                                                    <div v-if="payment_flag == 'cash'">
                                                                        <img src="/images/cash.png" style="width: 150px; height: auto">
                                                                    </div>
                                                                    <div v-if="payment_flag == 'cheque'">
                                                                        <img src="/images/cheque.png" style="width: 150px; height: auto">
                                                                    </div>
                                                                    <div v-if="payment_flag == 'credit-card'">
                                                                        <img src="/images/creditcard.png" style="width: 150px; height: auto">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                <div class="form-actions right">
                                                    <button @click="cancel()" type="button" class="btn btn-warning mr-1">
                                                        <i class="ft-x"></i> Cancel
                                                    </button>
                                                    <button v-if="order.id > 0 || order_created" @click="updateOrder()" type="button" class="btn btn-primary mr-1">
                                                        <i class="fa fa-save"></i> Update
                                                    </button>
                                                    <button v-if="order.id > 0 || order_created" @click="updateOrder(true)" type="button" class="btn btn-primary">
                                                        <i class="fa fa-bell"></i> Update & Notify
                                                    </button>
                                                    <button v-else @click="createOrder()" type="button" class="btn btn-primary">
                                                        <i class="fa fa-save"></i> Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="modal fade text-left show" id="sendInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Send Invoice</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-4 label-control" style="margin-top: 5px">Subject:</label>
                            <div class="col-md-8">
                                <input type="text" v-model="invoice_subject" v-bind:class="{'input-error-select' : error.invoice_subject}" class="form-control" id="invoice-subject">
                                <!-- <span v-if="error.invoice_subject" class="message-error">{{error_message.invoice_subject}}</span> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 label-control" style="margin-top: 5px">*Email:</label>
                            <div class="col-md-8">
                                <input type="text" v-model="send_email" v-bind:class="{'input-error-select' : error.send_email}" class="form-control" id="user-email">
                                <span v-if="error.send_email" class="message-error">{{error_message.send_email}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="sendInvoiceEmail(order.id)" type="button" class="btn btn-outline-primary" id="onhidebtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="sendQuote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Send Quote</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-4 label-control" style="margin-top: 5px">Subject:</label>
                            <div class="col-md-8">
                                <input type="text" v-model="invoice_subject_quote" v-bind:class="{'input-error-select' : error.invoice_subject_quote}" class="form-control" id="user-email-quote">
                                <!-- <span v-if="error.invoice_subject_quote" class="message-error">{{error_message.invoice_subject_quote}}</span> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 label-control" style="margin-top: 5px">*Email:</label>
                            <div class="col-md-8">
                                <input type="text" v-model="send_email_quote" v-bind:class="{'input-error-select' : error.send_email_quote}" class="form-control" id="user-email-quote">
                                <span v-if="error.send_email_quote" class="message-error">{{error_message.send_email_quote}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="sendQuoteEmail(order.id)" type="button" class="btn btn-outline-primary" id="onhidebtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="shippingAddress" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Add shipping address</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <radio-button v-if="customer_addresses.length > 0" :type="'address'" :radio_array="customer_addresses" @radioHandle="selectedAddress" :value="order.address_id"></radio-button>
                            <div v-else class="col-md-12">
                                <div class="input-group">
                                    <div class="skin skin-square"  style="margin-right:5px;">
                                        <div class="form-group" style="margin-bottom:10px;">
                                            <input id="address-new" type="radio" class="radio-address-new" name="address" value="0" checked>
                                            <label>New address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-show="show_new_address">
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping country:</label>
                                <div class="col-md-8">
                                    <select id="select2-countries" v-model="shipping_address.shipping_country" class="select2-placeholder form-control input-bordered"
                                        style="height: 50px !important; width: 100%" data-placeholder="Select country...">
                                        <option :value="-1"></option>
                                        <option v-for="(country, index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                    </select>
                                    <!-- <input type="text" v-model="shipping_address.shipping_country" v-bind:class="{'input-error-select' : error.shipping_country}"
                                            class="form-control" id="shipping_country"> -->
                                    <span v-if="error.shipping_country" class="message-error">{{error_message.shipping_country}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping address:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="shipping_address.shipping_address" v-bind:class="{'input-error-select' : error.shipping_address1}"
                                        class="form-control" id="shipping_address">
                                    <span v-if="error.shipping_address1" class="message-error">{{error_message.shipping_address1}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping address(cont):</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="shipping_address.shipping_address_cont" class="form-control" id="shipping_address_cont">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping city:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="shipping_address.shipping_city" v-bind:class="{'input-error-select' : error.shipping_city}" class="form-control"
                                        id="shipping_city">
                                    <span v-if="error.shipping_city" class="message-error">{{error_message.shipping_city}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping state:</label>
                                <div class="col-md-8">
                                    <div v-show="shipping_address.shipping_country == 'US'">
                                        <select id="select2-states" v-model="shipping_address.shipping_state" class="select2-placeholder form-control input-bordered"
                                            style="height: 50px !important; width: 100%" data-placeholder="Select state...">
                                            <option :value="-1"></option>
                                            <option v-for="(state, index) in states" :key="index" :value="state.abbreviation">{{state.name}}</option>
                                        </select>
                                    </div>
                                    <input v-show="shipping_address.shipping_country != 'US'" type="text" v-model="shipping_address.shipping_state" v-bind:class="{'input-error-select' : error.shipping_state}"
                                        class="form-control" id="shipping_state">
                                    <span v-if="error.shipping_state" class="message-error">{{error_message.shipping_state}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 label-control" style="margin-top: 5px">Shipping zip:</label>
                                <div class="col-md-8">
                                    <input type="text" v-model="shipping_address.shipping_zip" v-bind:class="{'input-error-select' : error.shipping_zip}" class="form-control"
                                        id="shipping_zip">
                                    <span v-if="error.shipping_zip" class="message-error">{{error_message.shipping_zip}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button v-if="shipping_address.id" @click="updateShippingAddres(shipping_address.id)" type="button" class="btn btn-outline-primary"
                            id="onhidebtn">Change</button>
                        <button v-else @click="addShippingAddress()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
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
</template>

<script>
    import Helpers from '../../../../../../misc/helpers.js';
    import OrdersServices from '../../services/OrdersServices';
    import DiscountServices from '../../services/DiscountServices';
    import AddressRadioButton from '../../../../../../components/AddressRadioButton.vue'
    export default {
        mixins: [Helpers, OrdersServices, DiscountServices],
        components: {
            'radio-button': AddressRadioButton
        },
        props: [
            // 'customers',
            'items',
            'categories',
            'payment_methods',
            'editable_order',
            'shipping_methods',
            'shipping_services',
            'app_url',
            'discount',
            'stores',
            'integration'
        ],
        data() {
            return {
                Helpers:Helpers,
                order: {
                    id: '',
                    payment_method_id: '',
                    shipping_method_id: '',
                    shipping_method_code: '',
                    shipping_urgency: '',
                    tracking_number: '',
                    customer_id: '',
                    order_number: '',
                    shipping_addresses: [],
                    order_products: [],
                    status: '',
                    total_amount: 0.00,
                    subtotal: 0.00,
                    shipping_cost: '',
                    final_shipping_cost: '',
                    shipping_status: '',
                    shipping_current_status: '',
                    origin: 'store',
                    partial: false,
                    min_payment: '',
                    notes: '',
                    paid_amount: 0.00,
                    total_tax_amount: 0.00,
                    discount_amount:0.00,
                    shipping_cost_estimated:'0.00',
                    address_id: 0
                },

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

                shipping_address: {
                    id: '',
                    shipping_address: '',
                    shipping_address_cont: '',
                    shipping_city: '',
                    shipping_state: '',
                    shipping_zip: '',
                    shipping_country: '',
                    primary: false
                },
                selected_shipping_address: {
                    id: '',
                    shipping_address: '',
                    shipping_address_cont: '',
                    shipping_city: '',
                    shipping_state: '',
                    shipping_zip: '',
                    shipping_country: '',
                    primary: false
                },

                cardInfo: {
                    card_holder: '',
                    card_number: '',
                    exp_date: '',
                    cvv: ''
                },
                currentUserInfo: {
                    id:0,
                    name: '',
                    lastname: '',
                    phone: '',
                    email: '',
                    avatar: ''
                },
                paypalEmail: '',

                customer: {
                    name: '',
                    lastname: '',
                    phone: '',
                    email: ''
                },
                shipping_status_collection: [
                    {
                        label: 'Pending',
                        value: 'pending'
                    },
                    {
                        label: 'Fullfilling',
                        value: 'fullfilling'
                    },
                    {
                        label: 'Fulfilled',
                        value: 'fulfilled'
                    },
                    {
                        label: 'Shipped',
                        value: 'shipped'
                    },
                    {
                        label: 'Delivered',
                        value: 'delivered'
                    },
                    {
                        label: 'Returned',
                        value: 'returned'
                    },
                    {
                        label: 'Cancelled',
                        value: 'cancelled'
                    }
                ],
                current_payment_methods: {},
                current_shipping_methods: {},
                current_shipping_services: {},
                //current_customers: {},
                current_products: {},
                product_variants: {},
                current_categories: {},

                selectables_products: [],
                selectables_variants: [],

                selected_payment_method: '',
                payment_flag: '',
                current_currency: {
                    name: 'American dolar',
                    symbol: '&#36;'
                },
                countries: [],
                states: [],
                carrier: '',
                error: {
                    customer: false,
                    shipping: false,
                    shipping_address1: false,
                    shipping_city: false,
                    shipping_zip: false,
                    shipping_state: false,
                    shipping_country: false,

                    category: false,
                    item: false,
                    qty: false,

                    name: false,
                    lastname: false,
                    email: false,
                    shipping_status: false,
                    coupon: false,
                    send_email: false,
                    send_email_quote: false,
                },
                error_message: {
                    customer: '',
                    shipping: '',
                    shipping_address1: '',
                    shipping_city: '',
                    shipping_zip: '',
                    shipping_state: '',
                    shipping_country: '',

                    category: '',
                    item: '',
                    qty: '',

                    name: '',
                    lastname: '',
                    email: '',
                    shipping_status: '',
                    coupon:'',
                    send_email:'',
                    send_email_quote:''
                },

                disable_shipping: false,
                disable_products: false,
                disable_cost_estimated: false,
                selectCustomer: false,
                item_id: 0,

                label_img: '',
                label_html: '',
                customer_name: '',
                customer_email:'',
                customer_company:'',
                customer_phone:'',
                order_status:'',
                shipping: false,
                edit_origin: false,
                edit_location: false,
                edit_order_number: false,
                prev_origin:'',
                prev_order_number:'',
                discount_code:'',
                show_discount: false,
                validate_coupon: '',
                applied_coupon:'',
                enabled_apply: true,
                total_amount:0.00,
                coupon_message:'',
                store:{
                    id:0,
                    name:''
                },
                order_created: false,
                customer_addresses: [],
                show_new_address: false,
                disable_shipping_address: false,
                send_email:'',
                send_email_quote:'',
                invoice_subject:'Properos Invoice',
                invoice_subject_quote:'Properos Quote',
                duplicate: false,
                showSwal:true
            }
        },
        methods: {
            selectedAddress(value){
                if(value.val == 0){
                    this.show_new_address = true;
                }else{
                    this.show_new_address = false;
                    for(var i in this.customer_addresses){
                        if(this.customer_addresses[i].id == value.val){
                            // this.order.shipping_addresses = [];
                            // this.order.shipping_addresses.push({
                            //     address_id: this.customer_addresses[i].id,
                            //     shipping_address : this.customer_addresses[i].address1,
                            //     shipping_address_cont : this.customer_addresses[i].address2,
                            //     shipping_city : this.customer_addresses[i].city,
                            //     shipping_zip : this.customer_addresses[i].zip,
                            //     shipping_state : this.customer_addresses[i].state,
                            //     shipping_country : this.customer_addresses[i].country,
                            // })
                            this.selected_shipping_address.id = this.customer_addresses[i].id;
                            this.selected_shipping_address.shipping_address = this.customer_addresses[i].address1;
                            this.selected_shipping_address.shipping_address_cont = this.customer_addresses[i].address2;
                            this.selected_shipping_address.shipping_city = this.customer_addresses[i].city;
                            this.selected_shipping_address.shipping_zip = this.customer_addresses[i].zip;
                            this.selected_shipping_address.shipping_state = this.customer_addresses[i].state;
                            this.selected_shipping_address.shipping_country = this.customer_addresses[i].country;
                        }
                    }
                }
                
            },
            paintOptions(index, value, i){
                var string = '';
                if(i>0){
                    string+=', '
                }
                string += index+':'+value;
                return string;
            },
            changeOrigin(){
                this.order.origin = this.prev_origin;
                this.edit_origin = false;
            },
            changeOrderNumber(){
                this.order.order_number = this.prev_order_number;
                this.edit_order_number = false;
            },
            createOrder() {
                if (this.order.customer_id >= 1) {
                    var self = this;
                    /* if (this.order.shipping_addresses.length > 0) { */
                        if (this.order.order_products.length > 0) {
                            swal({
                                title: "Order placement confirmation",
                                text: "When created, you can update the order. Note that once processing the payment, the order can't be changed",
                                icon: "warning",
                                buttons: true,
                                dangerMode: false,
                            }).then((confirm) => {
                                if (confirm) {
                                    var final_order = {
                                        id: self.order.id,
                                        user_id: self.order.customer_id,
                                        tax:self.store.tax,
                                        total_amount: parseFloat(self.order.total_amount),
                                        shipping_method: self.order.shipping_method_id,
                                        shipping_tracking: self.order.tracking_number,
                                        shipping_cost: self.order.shipping_cost,
                                        shipping_cost_estimated: self.order.shipping_cost_estimated,
                                        shipping_status: self.order.shipping_status,
                                        status: self.order_status,
                                        payment_method_id: self.order.payment_method_id,
                                        origin: self.order.origin,
                                        partial: self.order.partial,
                                        min_payment: self.order.min_payment,
                                        notes: self.order.notes,
                                        customer_company: self.customer_company,
                                        shipping_method_code: self.order.shipping_method_code,
                                        total_tax_amount: self.order.total_tax_amount
                                    }
                                    if (self.order.shipping_addresses.length > 0) {
                                        final_order.address_id = (self.order.shipping_addresses[0].address_id) ? self.order.shipping_addresses[0].address_id: 0;
                                        final_order.shipping_address_1 = self.order.shipping_addresses[0].shipping_address;
                                        final_order.shipping_address_2 = self.order.shipping_addresses[0].shipping_address_cont;
                                        final_order.shipping_city = self.order.shipping_addresses[0].shipping_city;
                                        final_order.shipping_zip = self.order.shipping_addresses[0].shipping_zip;
                                        final_order.shipping_state = self.order.shipping_addresses[0].shipping_state;
                                        final_order.shipping_country = self.order.shipping_addresses[0].shipping_country;
                                    }else{
                                        self.order.store_id = self.store.id;
                                    }
                                    for (var i in self.order.order_products) {
                                        self.order.order_products[i].id = '';
                                    }
                                    if(self.applied_coupon != ''){
                                        final_order.discount_code = self.applied_coupon;
                                        final_order.discount_amount = self.order.discount_amount;
                                    }
                                    final_order.order_items = self.order.order_products;
                                    
                                    $.LoadingOverlay("show");
                                    axios({
                                        method: 'post',
                                        url: '/api/orders/store',
                                        data: final_order
                                    }).then(response => {
                                       
                                        if (response.data.status == 1) {
                                            if(!self.duplicate){
                                                $.LoadingOverlay("hide");
                                                self.order.id = response.data.data.id;
                                                self.order.order_number = response.data.data.order_number;
                                                self.order.origin = response.data.data.origin;
                                                self.order_status = response.data.data.status;
                                                self.order.shipping_urgency = response.data.data.shipping_urgency;
                                                self.order.shipping_status = response.data.data.shipping_status;
                                                self.order_created = true;
                                                if (response.data.data.status != 'pending') {
                                                    self.disable_products = true;
                                                }
                                                for(var i in self.order.order_products){
                                                    if(self.order.order_products[i].id == ''){
                                                        for(var j in response.data.data.order_items){
                                                            if(response.data.data.order_items[j].item_id == self.order.order_products[i].item_id){
                                                                self.order.order_products[i].id = response.data.data.order_items[j].id;
                                                            }
                                                            self.order.order_products[i].order_id = response.data.data.order_items[j].order_id;
                                                        }
                                                    }
                                                }
                                                // this.order.order_products = response.data.data.order_items;
                                                swal({
                                                    title: "Success",
                                                    text: "The order has been created successfully.",
                                                    icon: "success",
                                                }).then((confirm) => {
                                                    if (confirm) {
                                                        // window.location.href = '/admin/store/orders';
                                                    }
                                                });
                                            }else{
                                                window.location.href = '/admin/store/edit-order/'+response.data.data.id
                                            }
                                        } else {
                                             $.LoadingOverlay("hide");
                                            swal("Error!", response.data.errors[0], "error");
                                        }
                                    }).catch((error) => {
                                         $.LoadingOverlay("hide");
                                        swal("Error!", error, "error");
                                    });
                                }
                            });

                        } else {
                            swal("This order is empty!", 'Please add at least one product to the order', "error");
                        }
                    /* } else {
                        swal("Shipping address not specified!", 'Please add a shipping address', "error");
                    } */
                }
                else {
                    this.error.customer = true;
                    this.error_message.customer = 'The customer is required';
                }

            },
            updateOrder(notify = false) {
                if (this.order.customer_id >= 0) {
                   /*  if (this.order.shipping_addresses.length > 0) { */
                        if (this.order.order_products.length > 0) {
                            if(this.order.shipping_method_code != ''){
                                if(typeof this.current_shipping_services[this.order.shipping_method_code] != 'undefined'){
                                    this.order.shipping_urgency = this.current_shipping_services[this.order.shipping_method_code].description;
                                }
                            }
                            var final_order = {
                                id: this.order.id,
                                tax:this.store.tax,
                                order_number: this.order.order_number,
                                user_id: this.order.customer_id,
                                total_amount: parseFloat(this.order.total_amount),
                                payment_method_id: this.order.payment_method_id,
                                order_items: this.order.order_products,
                                partial: this.order.partial,
                                min_payment: this.order.min_payment,
                                notes: this.order.notes,
                                status: this.order_status,
                                origin: this.order.origin,
                                notify: notify,
                                shipping_status: this.order.shipping_status,
                                total_tax_amount: this.order.total_tax_amount,
                                shipping_cost: this.order.shipping_cost,
                                shipping_cost_estimated: this.order.shipping_cost_estimated,
                                shipping_method: this.order.shipping_method_id,
                                shipping_method_code: this.order.shipping_method_code,
                                shipping_urgency: this.order.shipping_urgency,
                            }
                            if (this.order.shipping_addresses.length > 0) {
                                final_order.address_id = (this.order.shipping_addresses[0].address_id) ? this.order.shipping_addresses[0].address_id: 0;
                                final_order.shipping_address_1 = this.order.shipping_addresses[0].shipping_address;
                                final_order.shipping_address_2 = this.order.shipping_addresses[0].shipping_address_cont;
                                final_order.shipping_city = this.order.shipping_addresses[0].shipping_city;
                                final_order.shipping_zip = this.order.shipping_addresses[0].shipping_zip;
                                final_order.shipping_state = this.order.shipping_addresses[0].shipping_state;
                                final_order.shipping_country = this.order.shipping_addresses[0].shipping_country;
                            }
                            if (!this.disable_shipping) {
                                final_order.shipping_method = this.order.shipping_method_id;
                                final_order.shipping_tracking = this.order.tracking_number;
                                final_order.shipping_cost = this.order.shipping_cost;
                                // final_order.shipping_status = this.order.shipping_status;
                                /* if(this.order.shipping_method_id &&  this.order.tracking_number){

                                }
                                if (this.order.shipping_method_id && this.order.tracking_number) {
                                    if (!this.order.shipping_status || this.order.shipping_status == '-1') {
                                        swal("Shipping status field is required", '', "error");
                                        return 0;

                                    } else {
                                        final_order.shipping_status = this.order.shipping_status;
                                    }
                                } */
                            }else{
                                this.order.store_id = this.store.id;
                            }
                            if(this.applied_coupon != ''){
                                final_order.discount_code = this.applied_coupon;
                                final_order.discount_amount = this.order.discount_amount;
                            }
                            var self = this;
                            $.LoadingOverlay("show");
                            axios({
                                method: 'put',
                                url: '/api/orders/update/' + this.order.id,
                                data: final_order
                            }).then(response => {
                                $.LoadingOverlay("hide");
                                if (response.data.status == 1) {
                                    if(self.showSwal){
                                        swal({
                                            title: "Success",
                                            text: "The order has been updated successfully.",
                                            icon: "success",
                                        });
                                    }
                                    self.showSwal = true;
                                    this.prev_origin = this.order.origin;
                                    this.prev_order_number = this.order.order_number;
                                    if (response.data.data.order_items.length > 0) {
                                        this.order.shipping_status = response.data.data.shipping_status;
                                        this.order.shipping_current_status = response.data.data.shipping_status;
                                        this.order.status = response.data.data.status;
                                        this.order_status = this.order.status;
                                        this.order.order_products = [];
                                        for (var i in response.data.data.order_items) {
                                            var order_item = {
                                                id: response.data.data.order_items[i].id,
                                                order_id: response.data.data.order_items[i].order_id,
                                                name: response.data.data.order_items[i].name,
                                                description: response.data.data.order_items[i].description,
                                                taxes: response.data.data.order_items[i].taxes,
                                                price: parseFloat(response.data.data.order_items[i].price * 1),
                                                
                                                price_discount: (response.data.data.order_items[i].discount_percent) ? parseFloat(this.getPriceWithDiscount(response.data.data.order_items[i].price, response.data.data.order_items[i].discount_percent) * 1) : 0.00,
                                                discount_percent: (response.data.data.order_items[i].discount_percent) ? parseFloat(response.data.data.order_items[i].discount_percent * 1) : 0.00,
                                                
                                                item_id: response.data.data.order_items[i].item_id,
                                                qty: (response.data.data.order_items[i].qty % 1 != 0) ? response.data.data.order_items[i].qty : parseInt(response.data.data.order_items[i].qty),
                                                sku: response.data.data.order_items[i].sku,
                                                thumb_path: (response.data.data.order_items[i].item && response.data.data.order_items[i].item.files.length > 0) ? '/storage/' + response.data.data.order_items[i].item.files[0].thumb_path : (response.data.data.order_items[i].item.type == 'plan') ? '/images/icons/subscriptions.png' : '/images/item-placeholder.jpg',
                                                total_product_amount: response.data.data.order_items[i].qty * response.data.data.order_items[i].price
                                            }

                                            this.order.order_products.push(order_item);
                                        }
                                    }
                                    if (response.data.data.shipping_status == 'shipped') {
                                        this.disable_shipping = true;
                                        this.disable_cost_estimated = true;
                                    }
                                    if (response.data.data.status != 'pending' && response.data.data.status != null && response.data.data.status != '') {
                                        this.disable_products = true;
                                    }
                                    self.checkDiscount();
                                } else {
                                    if(Helpers.isAssoc(response.data.errors) > 0){
                                        var order_number = false;
                                        for(let field in response.data.errors){
                                            this.error_message[field] = response.data.errors[field][0];
                                            this.error[field] = true;
                                            if(field == 'order_number'){
                                                order_number = true;
                                                var message = 'The order number has already been taken.';
                                            }
                                        }
                                        if(order_number){
                                            swal("Error!", message, "error");
                                        }else{
                                            swal("Error!", 'Required fields missing', "error");
                                        }
                                    }else{
                                        swal('Error', response.data.errors[0], 'error');
                                    }
                                    $.LoadingOverlay("hide");
                                }
                            }).catch((error) => {
                                swal("Error!", error, "error");
                                 $.LoadingOverlay("hide");
                            });
                        }
                        else {
                            swal("This order is empty!", 'Please add at least one product to the order', "error");
                        }
                    /* } else {
                        swal("Shipping address not specified!", 'Please add a shipping address', "error");
                    } */
                }
                else {
                    this.error.customer = true;
                    this.error_message.customer = 'The customer is required';
                }
            },
            show() {
                console.log(this.category.active);
            },
            setPaymentMethod(e) {
                for (var i in this.current_payment_methods) {
                    if (this.order.payment_method_id == this.current_payment_methods[i].id) {
                        this.payment_flag = this.current_payment_methods[i].name
                    }
                }
            },
            getUserInfo(id) {
                if (id) {
                    $.LoadingOverlay("show");
                    axios({
                        method: 'get',
                        url: '/api/my-account/basic-user-info/' + id,
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            this.currentUserInfo.id = id;
                            this.currentUserInfo.email = response.data.data.email;
                            this.send_email = response.data.data.email;
                            this.send_email_quote = response.data.data.email;
                            this.currentUserInfo.phone = response.data.data.phone;
                            this.currentUserInfo.name = response.data.data.firstname;
                            this.currentUserInfo.lastname = response.data.data.lastname;
                            this.currentUserInfo.avatar = response.data.data.avatar ? '/storage/' + response.data.data.avatar : '/images/avatar-placeholder.png'
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            validateShippingInput() {
                if (this.shipping_address.shipping_address != '' && this.shipping_address.shipping_city != ''
                    && this.shipping_address.shipping_zip != '' && this.shipping_address.shipping_state != ''
                    && this.shipping_address.shipping_country != '') {
                    return true;
                }
                else {
                    if (this.shipping_address.shipping_address == '') {
                        this.error.shipping_address1 = true;
                        this.error_message.shipping_address1 = 'Shipping address is required';
                    }
                    if (this.shipping_address.shipping_city == '') {
                        this.error.shipping_city = true;
                        this.error_message.shipping_city = 'Shipping city is required';
                    }
                    if (this.shipping_address.shipping_zip == '') {
                        this.error.shipping_zip = true;
                        this.error_message.shipping_zip = 'Shipping zip code is required';
                    }
                    if (this.shipping_address.shipping_state == '') {
                        this.error.shipping_state = true;
                        this.error_message.shipping_state = 'Shipping state/province is required';
                    }
                    if (this.shipping_address.shipping_country == '') {
                        this.error.shipping_country = true;
                        this.error_message.shipping_country = 'Shipping country is required';
                    }
                }
            },
            validateItemsInput() {
                if (this.item_id > 0 && this.order_item.qty > 0) {
                    return true;
                }
                else {
                    if (this.order_item.item_id <= 0) {
                        this.error.item = true;
                        this.error_message.item = 'Select a product';
                    }
                    if (this.order_item.qty <= 0) {
                        this.error.qty = true;
                        this.error_message.qty = 'Product quantity must be specified';
                    }
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
            addItem() {
                if (this.validateItemsInput()) {
                    var self = this;
                    $.LoadingOverlay("show");
                    axios({
                        method: 'get',
                        url: '/api/items/show/' + this.item_id,
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            self.fillItemAdded(response.data.data);
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            getCarrierServices() {
                if(this.order.shipping_addresses.length > 0){
                    $.LoadingOverlay("show");
                    var params = {
                        'order_id': this.order.id,
                        'customer_address': this.order.shipping_addresses[0].shipping_address,
                        'customer_city': this.order.shipping_addresses[0].shipping_city,
                        'customer_state': this.order.shipping_addresses[0].shipping_state,
                        'customer_zip': this.order.shipping_addresses[0].shipping_zip,
                        'customer_country': this.order.shipping_addresses[0].shipping_country,
                        'items': this.order.order_products
                    };
                    axios({
                        method: 'post',
                        url: '/api/admin/carrier-services/' + this.carrier,
                        data: params
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1 && typeof response.data.data.error_code == 'undefined') {
                            this.current_shipping_services = "";
                            if (Object.keys(response.data.data).length > 0) {
                                this.current_shipping_services = Object.assign({}, this.current_shipping_services, response.data.data.methods);
                            }
                        } else if(response.data.data.error_code != '') {
                            swal("Error!", response.data.data.error_msg, "error");
                            this.order.shipping_method_id = 1;
                            $('#select2-shipping-method').val(this.order.shipping_method_id).trigger('change');
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                            this.order.shipping_method_id = 1;
                            $('#select2-shipping-method').val(this.order.shipping_method_id).trigger('change');
                        }
                    }).catch((error) => {
                        this.order.shipping_method_id = 1;
                        $('#select2-shipping-method').val(this.order.shipping_method_id).trigger('change');
                        swal("Error!", error, "error");
                    });
                }else{
                    this.order.shipping_method_id = 1;
                    $('#select2-shipping-method').val(this.order.shipping_method_id).trigger('change');
                    swal("Error!", 'Please, add a shipping address', "error");
                }
                
            },
            recalculate(){
                var subtotal = 0.00;
                var tax_base = 0.00;
                
                for(var i in this.order.order_products){
                    if(this.order.order_products[i].discount_percent != null){
                        subtotal += (this.order.order_products[i].price - (this.order.order_products[i].price * this.order.order_products[i].discount_percent/100)) * this.order.order_products[i].qty;
                        if(this.order.order_products[i].taxes > 0){
                            tax_base += (this.order.order_products[i].price - (this.order.order_products[i].price * this.order.order_products[i].discount_percent/100)) * this.order.order_products[i].qty;
                        }
                    }else{
                        subtotal += this.order.order_products[i].price * this.order.order_products[i].qty;
                        if(this.order.order_products[i].taxes > 0){
                            tax_base += this.order.order_products[i].price * this.order.order_products[i].qty;
                        }
                    }
                    
                }
                this.order.total_tax_amount = tax_base*this.store.tax /100;
                this.order.total_amount = subtotal + this.order.total_tax_amount;
                this.order.subtotal = subtotal ;
                if(this.order.id > 0){
                    this.showSwal = false;
                    this.updateOrder();
                }
            },
            fillItemAdded(data) {
                var item = Object.assign({}, data);
                item['thumb_path'] = (item.files.length > 0 && item.files[0].thumb_path && item.files[0].thumb_path != null) ? '/storage/' + item.files[0].thumb_path : '/images/item-placeholder.jpg',
                item['item_id'] = item.id;
                if (this.editable_order.id) {
                    item.order_id = this.order.id;
                }
                item.qty = (this.order_item.qty % 1 != 0) ? this.order_item.qty : parseInt(this.order_item.qty);
                item.taxes = item.taxable;
                //this.validate_coupon = false;
                //this.applied_coupon = '';
                //this.error.coupon = false;
                //this.order.discount_amount = 0.00;

                 if(this.applied_coupon != ''){
                   this.discount_code = this.applied_coupon;
                }

                if(item.discount_percent > 0){
                    item.price_discount = item.price - (item.price * item.discount_percent / 100);
                }

                this.order.order_products.push(item);
                this.recalculate();
                
                $("#select2-category").val(null).trigger('change');
                $("#select2-product").val(null).trigger('change');
                this.resetOrderItem();
            },
            addItemOnUpdate() {
                if (this.validateItemsInput()) {
                    var self = this;
                    $.LoadingOverlay("show");
                    axios({
                        method: 'get',
                        url: '/api/items/show/' + this.item_id,
                    }).then(response => {
                        if (response.data.status == 1) {
                            self.callUpdateItem(response.data.data);
                        } else {
                            $.LoadingOverlay("hide");
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        $.LoadingOverlay("hide");
                        swal("Error!", error, "error");
                    });
                }
            },
            callUpdateItem(data) {
                var _this = this;
                var item = Object.assign({}, data);
                // item.id = this.makeId();
                item.order_id = this.order.id;
                // item.price_discount = this.getPriceWithDiscount(item.price, item.discount_percent);
                item.qty = (this.order_item.qty % 1 != 0) ? this.order_item.qty : parseInt(this.order_item.qty);
                // item.price_discount = parseFloat(item.price_discount);
                item['item_id'] = item.id,
                // $.LoadingOverlay("show");
                axios({
                    method: 'post',
                    url: '/api/ordersItems/store',
                    data: item
                }).then(response => {
                    if (response.data.status == 1) {
                        item.id = response.data.data;
                        item.taxes = item.taxable;
                        item['thumb_path'] = (item.files.length > 0 && item.files[0].thumb_path && item.files[0].thumb_path != null) ? '/storage/' + item.files[0].thumb_path : '/images/item-placeholder.jpg';
                        _this.order.order_products.push(item);
                        if(_this.applied_coupon != ''){
                            _this.discount_code = _this.applied_coupon;
                        }
                        _this.recalculate();
                        // _this.checkDiscount();
                        $("#select2-category").val(null).trigger('change');
                        $("#select2-product").val(null).trigger('change');
                        _this.resetOrderItem();
                        $.LoadingOverlay("hide");
                    } else {
                        $.LoadingOverlay("hide");
                        swal("Error!", response.data.errors[0], "error");
                    }
                }).catch((error) => {
                    $.LoadingOverlay("hide");
                    swal("Error!", error, "error");
                });
            },
            addShippingAddress() {
                if(this.show_new_address){
                    if (this.validateShippingInput() == true) {
                        var shipping_address = Object.assign({}, this.shipping_address);
                        shipping_address.id = this.makeId();
                        if (this.order.shipping_addresses.length == 0) {
                            shipping_address.primary = true;
                        }
                         this.order.shipping_addresses = [];
                        this.order.shipping_addresses.push(shipping_address);
                        $('#shippingAddress').modal('hide');
                        this.resetShippingAddress();
                        this.getTax(shipping_address, this.getTaxCallback);
                    } else {
                        $('#shippingAddress').modal('show');
                    }
                }else{
                    this.order.shipping_addresses = [];
                    this.order.shipping_addresses.push({
                        address_id: this.selected_shipping_address.id,
                        shipping_address : this.selected_shipping_address.shipping_address,
                        shipping_address_cont : this.selected_shipping_address.shipping_address_cont,
                        shipping_city : this.selected_shipping_address.shipping_city,
                        shipping_zip : this.selected_shipping_address.shipping_zip,
                        shipping_state : this.selected_shipping_address.shipping_state,
                        shipping_country : this.selected_shipping_address.shipping_country,
                    })
                    this.getTax(this.selected_shipping_address, this.getTaxCallback);
                    $('#shippingAddress').modal('hide');
                }

            },
            getTaxCallback(response){
                if(response.status > 0){
                    if(this.order.status == 'pending' || this.order.status == null || this.order.status == ''){
                        this.store.id = response.data.id;
                        this.store.tax = response.data.tax;
                        this.recalculate();
                    }else{
                        this.recalculate();
                    }
                }else{
                    console.log(response);
                }
            },
            setPrimaryAddress() {
                this.shipping_address.primary = this.shipping_address.primary == true ? this.shipping_address.primary = false : this.shipping_address.primary = true;
            },
            updateTotalAmount(index) {
                if(this.order.order_products[index]['price'] != '-'){
                    // this.validate_coupon = false;
                    // this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                    // this.applied_coupon = '';
                    // this.order.discount_amount = 0.00;
                    if(this.applied_coupon != ''){
                        this.discount_code = this.applied_coupon;
                    }
                    this.error.coupon = false;
                    this.recalculate();
                }
            },
            removeItem(index) {
                if (this.order.order_products.length > 0) {
                    // this.validate_coupon = false;
                    // this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                    // this.applied_coupon = '';
                    // this.order.discount_amount = 0.00;

                    if(this.applied_coupon != ''){
                        this.discount_code = this.applied_coupon;
                    }
                    this.error.coupon = false;
                    var deleted_product = this.order.order_products.splice(index, 1);
                    if(this.order.order_products.length == 0){
                        this.validate_coupon = false;
                        this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                        this.applied_coupon = '';
                        this.order.discount_amount = 0.00;
                        this.discount_code = '';
                        this.recalculate();
                    }else{
                        this.recalculate();
                        // this.checkDiscount();
                    }
                }
            },
            removeItemOnUpdate(index, id) {
                var _this = this;
                if (this.order.order_products.length > 0) {
                    $.LoadingOverlay("show");
                    axios({
                        method: 'delete',
                        url: '/api/ordersItems/destroy/' + id
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            swal({
                                title: "Success",
                                text: "The order has been updated successfully.",
                                icon: "success",
                            });
                            // this.validate_coupon = false;
                            // this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                            // this.applied_coupon = '';
                            // this.order.discount_amount = 0.00;

                            if(_this.applied_coupon != ''){
                                _this.discount_code = _this.applied_coupon;
                            }
                            _this.error.coupon = false;
                            var deleted_product = _this.order.order_products.splice(index, 1);
                            if(_this.order.order_products.length == 0){
                                _this.validate_coupon = false;
                                _this.order.total_amount = (_this.order.total_amount*1 + _this.order.discount_amount*1);
                                _this.applied_coupon = '';
                                _this.discount_code = '';
                                _this.order.discount_amount = 0.00;
                            }
                             _this.recalculate();
                        } else {
                            swal("Error!", response.data.errors[0], "error");
                        }
                    }).catch((error) => {
                        swal("Error!", error, "error");
                    });
                }
            },
            setPrimary(index) {
                for (var i in this.order.shipping_addresses) {
                    if (i == index) {
                        this.order.shipping_addresses[i].primary = true;
                    } else {
                        this.order.shipping_addresses[i].primary = false;
                    }
                }
            },
            removeShippingAddres(index) {
                if (this.order.shipping_addresses.length > 0) {
                    this.order.shipping_addresses.splice(index, 1);
                    // this.store = this.stores;
                    // this.store['name'] = (typeof this.stores != 'undefined' && this.stores.label) ? this.stores.label : '';
                }
            },
            loadShippingAddres(index) {
                for (var i in this.order.shipping_addresses) {
                    if (i == index) {
                        this.shipping_address.id = this.order.shipping_addresses[i].id;
                        this.shipping_address.shipping_address = this.order.shipping_addresses[i].shipping_address;
                        this.shipping_address.shipping_address_cont = this.order.shipping_addresses[i].shipping_address_cont;
                        this.shipping_address.shipping_city = this.order.shipping_addresses[i].shipping_city;
                        this.shipping_address.shipping_state = this.order.shipping_addresses[i].shipping_state;
                        this.shipping_address.shipping_zip = this.order.shipping_addresses[i].shipping_zip;
                        this.shipping_address.shipping_country = this.order.shipping_addresses[i].shipping_country;
                        this.shipping_address.primary = this.order.shipping_addresses[i].primary;
                    }
                }
            },
            updateShippingAddres(id) {
                if(this.show_new_address){
                    if (this.validateShippingInput() == true) {
                        // this.validate_coupon = false;
                        // this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                        // this.applied_coupon = '';
                        // this.order.discount_amount = 0.00;
                        if(this.applied_coupon != ''){
                            this.discount_code = this.applied_coupon;
                        }
                        for (var i in this.order.shipping_addresses) {
                            if (this.order.shipping_addresses[i].id == id) {
                                this.order.shipping_addresses[i].id = this.shipping_address.id;
                                this.order.shipping_addresses[i].shipping_address = this.shipping_address.shipping_address;
                                this.order.shipping_addresses[i].shipping_address_cont = this.shipping_address.shipping_address_cont;
                                this.order.shipping_addresses[i].shipping_city = this.shipping_address.shipping_city;
                                this.order.shipping_addresses[i].shipping_state = this.shipping_address.shipping_state;
                                this.order.shipping_addresses[i].shipping_zip = this.shipping_address.shipping_zip;
                                this.order.shipping_addresses[i].shipping_country = this.shipping_address.shipping_country;
                                this.resetShippingAddress();
                                this.getTax(this.order.shipping_addresses[i], this.getTaxCallback);
                                this.order.shipping_method_id = 1;
                                this.order.shipping_method_code = ''; 
                                $('#shippingAddress').modal('hide');
                                
                            }
                        }
                    }
                }else{
                    this.order.shipping_addresses = [];
                    this.order.shipping_addresses.push({
                        address_id: this.selected_shipping_address.id,
                        shipping_address : this.selected_shipping_address.shipping_address,
                        shipping_address_cont : this.selected_shipping_address.shipping_address_cont,
                        shipping_city : this.selected_shipping_address.shipping_city,
                        shipping_zip : this.selected_shipping_address.shipping_zip,
                        shipping_state : this.selected_shipping_address.shipping_state,
                        shipping_country : this.selected_shipping_address.shipping_country,
                    })
                    this.getTax(this.selected_shipping_address, this.getTaxCallback);
                    $('#shippingAddress').modal('hide');
                }
            },
            isShippingAddressEmpty() {
                var self = this;
                Object.keys(self.shipping_address).forEach(function (key) {
                    if (self.shipping_address[key] != '') {
                        return false;
                    }
                });
                return true;
            },
            addCustomer() {
                var self = this;
                // this.validate_coupon = false;
                // this.order.total_amount = (this.order.total_amount*1 + this.order.discount_amount*1);
                // this.applied_coupon = '';
                // this.order.discount_amount = 0.00;
                
                this.error.coupon = false;

                var modal_ref = $('#newCustomer');
                // var customer_ref = this.initCustomer();

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
                            self.order.customer_id = response.data.data.id;
                            self.customer_email = response.data.data.email;
                            self.send_email = response.data.data.email;
                            self.send_emai_quote = response.data.data.email;
                            self.order.customer_email = response.data.data.email;
                            self.customer_company = response.data.data.company;
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

                            if(self.applied_coupon != ''){
                                self.discount_code = self.applied_coupon;
                            }
                            self.checkDiscount();
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
            resetShippingAddress() {
                var self = this;
                Object.keys(self.shipping_address).forEach(function (key) {
                    self.shipping_address[key] = '';
                });
            },
            resetCurrentUserInfo() {
                var self = this;
                Object.keys(self.currentUserInfo).forEach(function (key) {
                    self.currentUserInfo[key] = '';
                });
            },

            resetOrderItem() {
                var self = this;
                Object.keys(self.order_item).forEach(function (key) {
                    self.order_item[key] = '';
                });
            },
            setShippingAddress() {

            },
            getPriceWithDiscount(price, discount_percent) {
                var discount_percent = discount_percent ? parseFloat(discount_percent) : 0.00;
                var discount = (price * discount_percent) / 100;
                var discount = parseFloat(discount);
                return price - discount;
            },
            makeId() {
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                for (var i = 0; i < 10; i++)
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                return text;
            },
            resetShippingErrors() {
                var self = this;
                Object.keys(self.error).forEach(function (key) {
                    self.error[key] = false;
                });
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
            changeShippingInfo() {
                this.disable_shipping = false;
                this.disable_cost_estimated = false;
                $('#select2-shipping-shipping_status').val("-1");
                $('#select2-shipping-shipping_status').trigger('change');
            },
            cancel() {
                this.resetCurrentUserInfo();
                this.resetCustomerErrors();
                this.resetCustomerInfo();
                this.resetOrderItem();
                this.resetShippingAddress();
                this.resetShippingErrors();
                window.location.href = '/admin/store/orders'
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
                                query: "+*" + params.term.replace(/\s+/g, "* +*") + "*", // search term
                                fields: ['id', 'firstname', 'lastname'],
                                page: params.page,
                                with:['addresses'],
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
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.firstname + " " + repo.lastname + "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if (repo.firstname && repo.lastname) {
                            self.order.customer_id = repo.id;
                            self.customer_addresses = (repo.addresses) ? repo.addresses : [];
                            $('.skin-square input').iCheck({
                                checkboxClass: 'icheckbox_square-red',
                                radioClass: 'iradio_square-red',
                            });
                            return repo.firstname + " " + repo.lastname;
                        } else {
                            return repo.text;
                        }
                    } // omitted for brevity, see the source of this page
                });
            },
            initCategories() {
                var self = this;
                $("#select2-category").select2({
                    placeholder: 'Select customer...',
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
                                    fields: ['id', 'name','sku'],
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
                        }
                        return repo.name || repo.text;
                    } // omitted for brevity, see the source of this page
                });
            },
            getShippingInfo() {
                if (this.order.shipping_addresses.length > 0 && this.carrier != '') {
                    if (Object.keys(this.current_shipping_services).length > 0) {
                        if (this.order.shipping_method_code != '') {
                            var data = {
                                'carrier': this.carrier,
                                'shipping_method_code': this.order.shipping_method_code,
                                'address': this.order.shipping_addresses[0].shipping_address,
                                'city': this.order.shipping_addresses[0].shipping_city,
                                'zip': this.order.shipping_addresses[0].shipping_zip,
                                'state': this.order.shipping_addresses[0].shipping_state,
                                'country': this.order.shipping_addresses[0].shipping_country
                            }
                            if (this.order.shipping_addresses[0].shipping_address_cont != null) {
                                data.address = data.address + ' ' + this.order.shipping_addresses[0].shipping_address_cont;
                            }
                            $.LoadingOverlay("show");
                            this.getShippingInfoCall(data, this.order.id, this.getShippingInfoCallback);
                        } else {
                            swal('Error', 'Shipping service is required', 'error');
                        }
                    } else {
                        swal('Error', 'There are not automatic shipping services available at this time. You must enter the shipping information manually.', 'error');
                    }
                } else {
                    if (this.order.shipping_addresses.length == 0) {
                        swal('Error', 'Shipping address is required', 'error');
                    }
                    if (this.carrier == '') {
                        swal('Error', 'Shipping method is required', 'error');
                    }
                }
            },
            getShippingInfoCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.order.tracking_number = data.data.tracking_number;
                    this.label_img = data.data.label.image;
                    this.label_html = data.data.label.html;
                    this.order.shipping_cost = data.data.total_charges;
                    this.disable_shipping = true;
                    swal({
                        title: "Success",
                        text: "Shipping information generated successfully.",
                        icon: "success",
                    });
                } else if (code == '006') {
                    this.order.tracking_number = '';
                    this.label_img = '';
                    this.label_html = '';
                    swal("Error!", data.PrimaryErrorCode.Description, "error");
                } else if (code == '007') {
                    swal("Error!", data[0], "error");
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
                    if (this.order.shipping_addresses.length > 0) {
                        if (this.order.shipping_addresses[0].shipping_country == 'US') {
                            this.getStates();
                            $('#select2-states').val(self.order.shipping_addresses[0].shipping_state).trigger('change');
                        }
                    }
                }
            },
            getStatesCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    this.states = data;
                }
            },
            sendInvoiceEmail(id) {
                if(this.send_email != '' && Helpers.validateEmail(this.send_email)){
                    $.LoadingOverlay("show");
                    this.sendInvoiceEmailCall(id, this.send_email, this.invoice_subject,  this.sendInvoiceEmailCallback);
                }else{
                    this.error.send_email = true;
                    this.error_message.send_email = 'Email is required';
                }
            },
            sendInvoiceEmailCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    $('#sendInvoice').modal('hide');
                    toastr.success('Invoice email has been sent successfully', 'Invoice sent.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                } else {
                    toastr.error('Error sending invoice email', 'Error sending invoice.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                }
            },
            sendQuoteEmail(id) {
                if(this.send_email_quote != '' && Helpers.validateEmail(this.send_email_quote)){
                    $.LoadingOverlay("show");
                    this.sendQuoteEmailCall(id, this.send_email_quote, this.invoice_subject_quote, this.sendQuoteEmailCallback);
                }else{
                    this.error.send_email_quote = true;
                    this.error_message.send_email_quote = 'Email is required';
                }
            },
            sendQuoteEmailCallback(code, msg, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    $('#sendQuote').modal('hide');
                    toastr.success('Quote email has been sent successfully', 'Quote sent.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                } else {
                    toastr.error('Error sending Quote email', 'Error sending Quote.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                }
            },
            changePaymentStatus(status){
                this.order_status = status;
                if(status == 'paid'){
                }
            },
            changeOrderStatus(status){
                this.order.shipping_status = status;
            },
            checkDiscount(){
            if(this.discount_code != ''){
                var final_order = {
                    id: this.order.id,
                    user_id: this.order.customer_id,
                    total_amount: this.order.total_amount,
                    shipping_method: this.order.shipping_method_id,
                    shipping_tracking: this.order.tracking_number,
                    shipping_cost: this.order.shipping_cost,
                    shipping_status: this.order.shipping_status,
                    status: this.order_status,
                    payment_method_id: this.order.payment_method_id,
                    origin: this.order.origin,
                    partial: this.order.partial,
                    min_payment: this.order.min_payment,
                    notes: this.order.notes,
                    customer_company: this.customer_company,
                    total_tax_amount: this.order.total_tax_amount,
                    store_id: this.store.id
                }
                if (this.order.shipping_addresses.length > 0) {
                    final_order.shipping_address_1 = this.order.shipping_addresses[0].shipping_address;
                    final_order.shipping_address_2 = this.order.shipping_addresses[0].shipping_address_cont;
                    final_order.shipping_city = this.order.shipping_addresses[0].shipping_city;
                    final_order.shipping_zip = this.order.shipping_addresses[0].shipping_zip;
                    final_order.shipping_state = this.order.shipping_addresses[0].shipping_state;
                    final_order.shipping_country = this.order.shipping_addresses[0].shipping_country;
                }
                final_order.order_items = this.order.order_products;
                $.LoadingOverlay("show");
                this.validateDiscount(this.discount_code, final_order, this.validateDiscountCallback);
            }
        },
        validateDiscountCallback(response){
            this.validate_coupon = '';
            $.LoadingOverlay("hide");
            if(response.status > 0){
                this.error.coupon = false;
                    this.order.id= response.data.order.id,
                    this.order.total_amount= parseFloat(response.data.order.total_amount),
                    this.order.total_tax_amount= (response.data.order.total_tax_amount) ? parseFloat(response.data.order.total_tax_amount): 0.00;
                    this.order.shipping_cost_estimated= (response.data.order.shipping_cost_estimated) ? parseFloat(response.data.order.shipping_cost_estimated) : 0.00;
                    this.order.discount_amount= (response.data.order.discount_amount) ? parseFloat(response.data.order.discount_amount) : 0.00;
                    this.order.discount_code= (response.data.order.discount_code) ? response.data.order.discount_code : '';
                    this.applied_coupon = this.order.discount_code;
                    this.paintCoupon(response.data.discount);
                    this.discount_code = '';
            }else{
                this.invalidDiscount(response);
            }
        },
        invalidDiscount(response){
                if(this.order.discount_code != '')
                        // this.discount_code = this.order.discount_code;
                    this.error.coupon = true;
                    this.error_message.coupon = response.errors[0];
                    this.order.total_amount= (response.data.total_amount) ? parseFloat(response.data.total_amount): this.order.total_amount + this.order.discount_amount;
                    this.order.discount_amount= 0.00;
                    this.order.total_tax_amount= (response.data.total_tax_amount) ? parseFloat(response.data.total_tax_amount): this.order.total_tax_amount;
                    this.order.shipping_cost_estimated= (response.data.shipping_cost_estimated) ? parseFloat(response.data.shipping_cost_estimated) : this.order.shipping_cost_estimated;
                    this.order.discount_code= '';
                    swal('Error', response.errors[0], 'error');
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
            duplicateOrder(){
                $.LoadingOverlay("show");
                this.order.id = 0;
                this.order.status = 'pending';
                this.order.shipping_status = 'pending';
                this.order.order_number = '';
                this.duplicate = true;
                this.order_status = 'pending';
                setTimeout(function(){
                    $.LoadingOverlay("hide");
                },100);
            },
    },
        watch: {
            'customer.name'(val) {
                if (val != '') {
                    this.error.name = false;
                }
            },
            'order.tracking_number'(val) {
                if (val != '') {
                     this.disable_shipping_address = true;
                }
            },
            'order.shipping_status'(val) {
                if ((val == 'pending' || val == 'fullfiling' || val == 'fulfilled') && this.order.tracking_number == '') {
                    this.disable_shipping_address = false;
                }else{
                    this.disable_shipping_address = true;
                }
            },
            'customer.lastname'(val) {
                if (val != '') {
                    this.error.lastname = false;
                }
            },
            'customer.email'(val) {
                if (val != '') {
                    this.error.email = false;
                }
            },
            'order.customer_id'(val) {
                if (val >= 0) {
                    this.error.customer = false;
                }
            },
            'error.customer'(val) {
                if (val) {
                    $('#select2-customer').addClass('input-error-select');
                }
            },

            //CLEAR ERRORS
            'shipping_address.shipping_address'(val) {
                if (val != '') {
                    this.error.shipping_address1 = false;
                    this.error_message.shipping_address1 = '';
                }
            },
            'shipping_address.shipping_city'(val) {
                if (val != '') {
                    this.error.shipping_city = false;
                    this.error_message.shipping_city = '';
                }
            },
            'shipping_address.shipping_zip'(val) {
                if (val != '') {
                    this.error.shipping_zip = false;
                    this.error_message.shipping_zip = '';
                }
            },
            'shipping_address.shipping_state'(val) {
                if (val != '') {
                    this.error.shipping_state = false;
                    this.error_message.shipping_state = '';
                }
            },
            'shipping_address.shipping_country'(val) {
                if (val != '') {
                    this.error.shipping_country = false;
                    this.error_message.shipping_country = '';
                }
            },

            // 'order_item.category_id'(val) {
            //     if (val > 0) {
            //         this.error.category = false;
            //         this.error_message.category = '';
            //     }
            // },
            'item_id'(val) {
                if (val > 0) {
                    this.error.item = false;
                    this.error_message.item = '';
                }
            },
            'order_item.qty'(val) {
                if (val > 0) {
                    this.error.qty = false;
                    this.error_message.qty = '';
                }
            },
            'discount_code'(val) {
                if (val != '') {
                    this.enabled_apply = false;
                }else{
                    this.enabled_apply = true;
                }
            },
            'order.shipping_method_id'(val){
                this.current_shipping_services = {};
                
                if(val > 0){
                    this.shipping = true;
                }else{
                    this.shipping = false;
                }
                if(val == 2){
                    if(this.order.shipping_status == 'pending' || this.order.shipping_status == 'fullfiling' || this.order.tracking_number == ''){
                        if(this.integration.ups.api_integration == true){
                            this.disable_cost_estimated = true;
                        }else{
                            this.disable_cost_estimated = false;
                        }
                        this.carrier = 'ups';
                        this.getCarrierServices();
                    }else{
                        var code = this.order.shipping_method_code;
                        this.current_shipping_services[code] = {
                            id: this.order.shipping_method_code,
                            cost: this.order.shipping_cost_estimated,
                            description: this.order.shipping_urgency,
                            code: this.order.shipping_method_code
                        };
                    }
                }else{
                    this.order.shipping_cost_estimated = 0.00;
                    this.disable_cost_estimated = false;
                    this.order.shipping_method_code = '';
                }
            },
            'order.shipping_method_code'(val){
                if(val != '' && val != '03'){
                    this.order.total_amount -=  this.order.shipping_cost_estimated;
                    if(typeof this.current_shipping_services[val] != 'undefined'){
                        this.order.shipping_cost_estimated = this.current_shipping_services[val].cost;
                    }
                }else{
                    this.order.total_amount -=  this.order.shipping_cost_estimated;
                    this.order.shipping_cost_estimated = '0.00';
                }
                this.order.total_amount +=  this.order.shipping_cost_estimated*1;
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            this.current_payment_methods = this.payment_methods;
            this.current_shipping_methods = this.shipping_methods;

            /*  if (Object.keys(this.shipping_services).length > 0) {
                 this.current_shipping_services = Object.assign({}, this.current_shipping_services, this.shipping_services);
             } */

            this.current_products = this.items;
            this.current_categories = this.categories;
            if(this.stores != null){
                this.store = this.stores;
                this.store.name = this.stores.label

            }
            if (this.editable_order.id) {
                this.order.id = this.editable_order.id ? this.editable_order.id : '';
                this.order.tax = this.editable_order.tax ? this.editable_order.tax : 0.00;
                this.store.tax = this.order.tax;
                this.order.payment_method_id = this.editable_order.payment_method_id ? this.editable_order.payment_method_id : '';
                this.order.shipping_method_id = this.editable_order.shipping_method ? this.editable_order.shipping_method.id : '';
                if(this.order.shipping_method_id > 0){
                    this.shipping = true;
                }else{
                    this.shipping = false;
                }
                if (this.editable_order.status != 'pending' && this.editable_order.status != null && this.editable_order.status != '') {
                    this.disable_products = true;
                }
                this.order.shipping_method_code = this.editable_order.shipping_method_code ? this.editable_order.shipping_method_code : '';
                this.order.shipping_cost = this.editable_order.shipping_cost ? this.editable_order.shipping_cost : '';
                this.order.shipping_status = this.editable_order.shipping_status ? this.editable_order.shipping_status : '';
                this.order.shipping_current_status = this.editable_order.shipping_status ? this.editable_order.shipping_status : '';
                this.order.shipping_urgency = this.editable_order.shipping_urgency ? this.editable_order.shipping_urgency : '';
                this.order.partial = this.editable_order.partial ? this.editable_order.partial : false;
                this.order.min_payment = this.editable_order.min_payment ? this.editable_order.min_payment : '';
                this.order.notes = this.editable_order.notes ? this.editable_order.notes : '';
                if (this.order.shipping_status == 'shipped') {
                    this.disable_shipping = true;
                    this.disable_cost_estimated = true;
                }
                this.order.status = this.editable_order.status ? this.editable_order.status : '';
                this.order_status = this.order.status;
                this.order.tracking_number = this.editable_order.shipping_tracking ? this.editable_order.shipping_tracking : '';
                
                if (this.editable_order.user_id > 0) {
                    this.order.customer_id = this.editable_order.user_id;
                    this.selectCustomer = false;
                    if(this.editable_order.customer && this.editable_order.customer.addresses && this.editable_order.customer.addresses.length > 0){
                        this.customer_addresses = this.editable_order.customer.addresses;
                        for(var i in this.customer_addresses){
                            if(this.customer_addresses[i].id == this.editable_order.address_id){
                                this.order.address_id = this.editable_order.address_id;
                            }
                        }
                    }
                } else {
                    this.order.customer_id = 0;
                    // this.selectCustomer = true;
                }
                if(this.order.address_id == 0){
                    this.show_new_address = true;
                }
                this.customer_name = this.editable_order.customer_name;
                this.customer_company = this.editable_order.customer_company;
                this.customer_email = this.editable_order.customer_email;
                this.customer_phone = this.editable_order.customer_phone;
                // this.order.total_amount = this.editable_order.total_amount ? (this.editable_order.total_amount * 1) - this.editable_order.total_tax_amount * 1 - this.editable_order.shipping_cost_estimated *1 : '';
                this.order.total_amount = this.editable_order.total_amount ? (this.editable_order.total_amount * 1) : '';
                this.order.discount_amount = this.editable_order.discount_amount ? (this.editable_order.discount_amount * 1) : 0.00;
                this.order.subtotal = this.editable_order.subtotal ? (this.editable_order.subtotal * 1) : 0.00;
                this.total_amount = this.order.total_amount + this.order.discount_amount;
                this.order.total_tax_amount = this.editable_order.total_tax_amount ? (this.editable_order.total_tax_amount * 1) : 0.00;
                this.order.shipping_cost_estimated = this.editable_order.shipping_cost_estimated ? (this.editable_order.shipping_cost_estimated * 1) : '0.00';
                this.order.origin = this.editable_order.origin ? this.editable_order.origin : '';
                this.prev_origin = this.order.origin;
                this.order.order_number = this.editable_order.order_number ? this.editable_order.order_number : '';
                this.prev_order_number = this.order.order_number;
                this.order.token = this.editable_order.token ? this.editable_order.token : null;
                this.order.discount_code = this.editable_order.discount_code ? this.editable_order.discount_code : '';

                if(this.order.discount_code != ''){
                    this.validate_coupon = this.order.discount_code;
                    this.applied_coupon = this.order.discount_code;
                    switch(this.discount.discount_type){
                        case 'percentage':
                            var off = (this.discount.discount_value % 1 != 0) ? this.discount.discount_value : (this.discount.discount_value*1).toFixed();
                            var text = (this.discount.apply_to != 'order') ? ' on selected items' : ' on entire order';
                            this.coupon_message = off + '% off' + text;
                            break;
                        case 'fixed_amount':
                            var text = (this.discount.apply_to != 'order') ? ' on selected items' : ' on entire order';
                            this.coupon_message = '$ '+ this.discount.discount_value + ' off' + text;
                            break;
                        case 'free_shipping':
                            this.coupon_message = 'Free shipping';
                            break;
                        case 'buy_x_get_y':
                            var off = (this.discount.discount_value > 0 && this.discount.discount_value != 100) ? (this.discount.discount_value % 1 != 0) ? this.discount.discount_value : (this.discount.discount_value*1).toFixed() : (this.discount.discount_value == 100) ? ' free' : '';
                            var text1 = (this.discount.discount_value*1 != 100) ? ' at ' : '';
                            var text2 = (this.discount.discount_value*1 != 100) ? ' % off ' : '';
                            this.coupon_message = 'Buy '+ this.discount.buy_qty + ' get '+ this.discount.get_qty+ text1 + off + text2;
                            break;
                    }
                }
                if (this.editable_order) {
                    if (this.editable_order.id > 0) {
                        if (this.editable_order.order_items.length > 0) {
                            
                            for (var i in this.editable_order.order_items) {
                                var order_item = {
                                    id: this.editable_order.order_items[i].id,
                                    order_id: this.editable_order.order_items[i].order_id,
                                    name: this.editable_order.order_items[i].name,
                                    description: this.editable_order.order_items[i].description,
                                    taxes: parseFloat(this.editable_order.order_items[i].taxes),
                                    tax_percent: (this.editable_order.order_items[i].item) ? parseFloat(this.editable_order.order_items[i].item.tax_percent) : 0.00,
                                    price: parseFloat(this.editable_order.order_items[i].price * 1),

                                    price_discount: (this.editable_order.order_items[i].discount_percent) ? parseFloat(this.getPriceWithDiscount(this.editable_order.order_items[i].price, this.editable_order.order_items[i].discount_percent) * 1) : 0.00,
                                    discount_percent: (this.editable_order.order_items[i].discount_percent) ? parseFloat(this.editable_order.order_items[i].discount_percent * 1) : 0.00,
                                   
                                    item_id: this.editable_order.order_items[i].item_id,
                                    qty: (this.editable_order.order_items[i].qty % 1 != 0) ? this.editable_order.order_items[i].qty : parseInt(this.editable_order.order_items[i].qty),
                                    sku: this.editable_order.order_items[i].sku,
                                    thumb_path: (this.editable_order.order_items[i].item && this.editable_order.order_items[i].item.files.length > 0) ? '/storage/' + this.editable_order.order_items[i].item.files[0].thumb_path : (!this.editable_order.order_items[i].item || (this.editable_order.order_items[i].item && this.editable_order.order_items[i].item.type == 'plan')) ? '/images/icons/subscriptions.png' : '/images/item-placeholder.jpg',
                                    total_product_amount: this.editable_order.order_items[i].qty * this.editable_order.order_items[i].price,
                                    options: this.editable_order.order_items[i].options
                                }
                                this.order.order_products.push(order_item);
                            }
                        }
                        if(this.editable_order.shipping_address1 != null || this.editable_order.shipping_city != null || this.editable_order.shipping_state!= null
                        || this.editable_order.shipping_zip != null || this.editable_order.shipping_country != null){
                            var shipping_adrres = {
                                id: this.editable_order.id,
                                shipping_address: this.editable_order.shipping_address1,
                                shipping_address_cont: this.editable_order.shipping_address2,
                                shipping_city: this.editable_order.shipping_city,
                                shipping_state: this.editable_order.shipping_state,
                                shipping_zip: this.editable_order.shipping_zip,
                                shipping_country: this.editable_order.shipping_country,
                                primary: this.editable_order.primary
                            }
                            this.order.shipping_addresses.push(shipping_adrres);
                        }
                    }
                    
                }
            }else{
                this.order.customer_id = '';
                this.selectCustomer = true;
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                $("#select2-countries").select2({
                     matcher: self.matchCustom
                });
                $("#select2-states").select2({
                     matcher: self.matchCustom
                });

                $(".icons-tab-steps").steps({
                    headerTag: "h6",
                    bodyTag: "fieldset",
                    transitionEffect: "fade",
                    titleTemplate: '<span class="step">#index#</span> #title#',
                    labels: {
                        finish: 'Submit'
                    },
                    onFinished: function (event, currentIndex) {
                        alert("Form submitted.");
                    },

                    onInit: function (event, currentIndex) {

                    }
                });

                // $("#select2-product").select2();
                // $("#select2-category").select2();
                // $("#select2-category").val(null).trigger("change");
                $("#select2-variant").select2();
                $("#select2-shipping-method").select2();
                $("#select2-shipping-services").select2();
                $("#select2-shipping-shipping_status").select2();
                $("select2-payment-method").select2();

                $("#select2-location").select2({
                    placeholder: 'Select customer...',
                    ajax: {
                        url: '/api/admin/locations/search',
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
                                fields: ['id','label','address','country', 'state', 'city', 'zip','tax'],
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
                        // var markup = "<div class='select2-result-repository clearfix'>" + ((repo.address != null) ? repo.address+',' : '')+' '+repo.label +' '+((repo.zip != null) ? ','+repo.zip : '')+ "</div>";
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.label+ "</div>";
                        return markup;
                    }, // omitted for brevity, see the source of this page
                    templateSelection: function (repo) {
                        if (repo.label) {
                            self.store.id = repo.id;
                            // var label =  ((repo.address != null) ? repo.address+',' : '')+' '+repo.label +' '+((repo.zip != null) ? ','+repo.zip : '');
                            self.store.name = repo.label;
                            return repo.label;
                        } else {
                            return repo.text;
                        }
                    } // omitted for brevity, see the source of this page
                });

                self.initProducts();
                self.initCustomer();
                self.initCategories();

                $('#partial').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#partial').on('ifChecked', function (event) {
                    self.order.partial = true;
                });

                $('#partial').on('ifUnchecked', function (event) {
                    self.order.partial = false;
                    self.order.min_payment = '';
                });

                if (self.order.partial == 1) {
                    $('#partial').iCheck('check');
                } else {
                    $('#partial').iCheck('uncheckeck');
                }


                // $('#select2-category').on('change', function (e) {
                //     self.selectables_products = [];
                //     if ($('#select2-category').select2('data').length > 0) {
                //         self.order_item.category_id = $('#select2-category').select2('data')[0].id;
                //         for (var i in self.current_products) {
                //             if (self.current_products[i].category_id == $('#select2-category').select2('data')[0].id) {
                //                 self.selectables_products.push(self.current_products[i]);
                //             }
                //         }
                //     }

                //     $("#select2-product").val(null).trigger("change");
                //     $("#select2-product").select2('destroy');
                //     $("#select2-product").select2();
                // });

                $('#select2-shipping-method').on('change', function (e) {
                    self.order.shipping_method_id = parseInt($('#select2-shipping-method').select2('data')[0].id);
                    if(self.order.shipping_method_id > 0){
                        self.shipping = true;
                    }else{
                        self.shipping = false;
                    }
                    self.carrier = $('#select2-shipping-method').select2('data')[0].title;
                    if(self.carrier == 'ups'){
                        if(self.order.shipping_status == 'pending' || self.order.shipping_status == 'fullfiling' || self.order.tracking_number == ''){
                            if(self.integration.ups.api_integration == true){
                                self.disable_cost_estimated = true;
                            }else{
                                self.disable_cost_estimated = false;
                            }
                            self.getCarrierServices();
                        }else{
                            var code = self.order.shipping_method_code;
                            self.current_shipping_services[code] = {
                                id: self.order.shipping_method_code,
                                cost: self.order.shipping_cost_estimated,
                                description: self.order.shipping_urgency,
                                code: self.order.shipping_method_code
                            };
                        }
                    }else{
                        self.order.shipping_cost_estimated = 0.00;
                        self.order.shipping_method_code = '';
                        self.disable_cost_estimated = false;
                    }
                    
                });
                $('#select2-shipping-services').on('change', function (e) {
                    if (Object.keys(self.current_shipping_services).length > 0) {
                        if(typeof $('#select2-shipping-services').select2('data')[0] != 'undefined'){
                            self.order.shipping_method_code = $('#select2-shipping-services').select2('data')[0].id;
                        }
                        if(self.order.shipping_method_code != '' && self.order.shipping_method_code != '03'){
                            self.order.total_amount -=  self.order.shipping_cost_estimated;
                            if(typeof self.current_shipping_services[self.order.shipping_method_code] != 'undefined'){
                                if(self.order.status == 'pending'){
                                    self.order.shipping_cost_estimated = self.current_shipping_services[self.order.shipping_method_code].cost*1;
                                }
                            }
                        }else{
                            self.order.total_amount -=  self.order.shipping_cost_estimated;
                            self.order.shipping_cost_estimated = 0.00;
                        }
                        self.order.total_amount +=  self.order.shipping_cost_estimated*1;
                    }
                });

                $('#select2-shipping-shipping_status').on('change', function (e) {
                    self.order.shipping_status = $('#select2-shipping-shipping_status').select2('data')[0].id;
                });

                $('#select2-customer').on('change', function (e) {
                    self.order.customer_id = $('#select2-customer').select2('data').length > 0 ? parseInt($('#select2-customer').select2('data')[0].id) : '';
                    self.getUserInfo(self.order.customer_id);
                    // setTimeout(function(){
                    //     $('.skin-square input').iCheck({
                    //         checkboxClass: 'icheckbox_square-red',
                    //         radioClass: 'iradio_square-red',
                    //     });
                    // })
                });

                $('select2-payment-method').on('change', function (e) {
                    self.order.payment_method_id = parseInt($('select2-payment-method').select2('data')[0].id);
                });

                // $('#select2-product').on('change', function (e) {
                //     if ($('#select2-product').select2('data').length > 0) {
                //         for (var i in self.current_products) {
                //             if (self.current_products[i].id == $('#select2-product').select2('data')[0].id) {
                //                 self.order_item.item_id = self.current_products[i].id;
                //                 self.order_item.name = self.current_products[i].name;
                //                 self.order_item.description = self.current_products[i].description;
                //                 self.order_item.price = self.current_products[i].price;
                //                 self.order_item.discount_percent = self.current_products[i].discount_percent;
                //                 if (self.current_products[i].files.length > 0) {
                //                     self.order_item.thumb_path = '/storage/' + self.current_products[i].files[0].thumb_path;
                //                 } else {
                //                     self.order_item.thumb_path = '/images/item-placeholder.jpg';
                //                 }
                //             }
                //         }
                //     }

                // });

                $('#select2-variant').on('change', function (e) {
                    self.order_item.variant_id = parseInt($('#select2-variant').select2('data')[0].id);
                });


                $('#onhidebtn').on('click', function () {

                });

                $('#shippingAddress').on('shown.bs.modal', function () {
                    
                    if(self.customer_addresses.length == 0){
                        self.show_new_address = true;
                        $('#address-new').iCheck({
                            checkboxClass: 'icheckbox_square-red',
                            radioClass: 'iradio_square-red',
                        });
                    }
                    //CHECKBOXES
                    $('#setPrimaryShipping').on('ifChecked', function (event) {
                        console.log(event);
                    });

                    $('#setPrimaryShipping').on('ifUnchecked', function (event) {

                    });

                    $.LoadingOverlay("show");
                    self.getCountriesCall(self.getCountriesCallback);
                });
                //ON UPDATE
                if (self.editable_order.id > 0) {
                    // $('#select2-customer').val(self.editable_order.user_id);
                    // $('#select2-customer').trigger('change');
                    self.getUserInfo(self.order.customer_id);
                    if (self.order.shipping_method_id) {
                        $('#select2-shipping-method').val(self.order.shipping_method_id).trigger('change');
                    }

                    if (self.order.shipping_method_code) {
                        $('#select2-shipping-services').val(self.order.shipping_method_code).trigger('change');
                    }


                }

                $('#shippingAddress').on('hidden.bs.modal', function () {
                    self.resetShippingAddress();
                    self.resetShippingErrors();
                });

                $('#newCustomer').on('hidden.bs.modal', function () {
                    /* $("#select2-customer").val(null).trigger("change");
                    $("#select2-customer").select2('destroy');
                    $("#select2-customer").select2(); */
                    self.resetCustomerInfo();
                    self.resetCustomerErrors();
                });

                $('#newCustomer').on('show.bs.modal', function () {
                    self.resetCustomerInfo();
                    self.resetCurrentUserInfo();
                    // $("#select2-customer").val(null).trigger("change");
                    // $("#select2-customer").select2('destroy');
                    // self.initCustomer();
                    // $("#select2-customer").select2();
                });

                $('#sendInvoice').on('hidden.bs.modal', function () {
                   self.error.send_email = false;
                   self.send_email = self.currentUserInfo.email;
                });

                $('#sendQuote').on('hidden.bs.modal', function () {
                   self.error.send_email_quote = false;
                   self.send_email_quote = self.currentUserInfo.email;
                });


            });

            $('#select2-countries').on('change', function (e) {
                self.shipping_address.shipping_country = $('#select2-countries').select2('data')[0].id;
                self.shipping_address.shipping_state = "";
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

          
        }
    }
</script>
<style>
    .select2-container {
        width: 100% !important;
    }

    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        padding-top: 10px;
        font-size: 12px;
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


    .select2-selection {
        height: 50px !important;
    }

    .select2-selection__placeholder {
        margin-top: 0px !important;
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

    .edit-class{
        display: none;
    }
    #pay_status:hover .edit-class{
        display: inline-block;
    }
    .status-class{
        display: none;
    }
    .origin-class{
        display: none;
    }
    .order-number-class{
        display: none;
    }
    #status:hover .status-class{
        display: inline-block;
    }
    #origin:hover .origin-class{
        display: inline-block;
    }
    #store:hover .origin-class{
        display: inline-block;
    }
    #order_number:hover .order-number-class{
        display: inline-block;
    }
    .icons-custom{
        width:20px;
        height:20px;
    }
    .custom-form-control{
        padding-left: 10px!important;
        padding-top:0px;
        padding-bottom:2px;
        border: none!important;
        border-bottom: 1px solid #404E67!important;
        border-radius: 0!important;
        margin-top: 0!important;
        background-color: transparent!important;
    }
    .custom-form-control:focus {
        background-color: transparent;
    }
    .location-class .select2-container--default .select2-selection--single{
        background-color: #fff;
        border: none;
        border-bottom: 1px solid #404E67;
        border-radius: 0px;
    }

    .location-class .select2-container *:focus {
        outline: 0;
        background-color: transparent;
        border-color: transparent;
        box-shadow: none;    
    }
</style>