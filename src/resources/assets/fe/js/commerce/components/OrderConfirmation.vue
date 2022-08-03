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
                <div class="text-center">
                    <template v-if="confirmation_status == 'success'">
                        <h5 style="color: rgb(16, 116, 41)">(: Thanks for shopping with us !!!</h5>
                        <div style="font-size: 24px">Your order has been placed.</div>
                        <div style="font-size: 20px">Order number:
                            <span style="color: green">
                                <strong>{{current_order.order_number}}</strong>
                            </span>
                        </div>
                    </template>
                    <template v-else-if="confirmation_status == 'error'">
                        <h5 style="color: brown">): Opss... it have been an issue placing your order.</h5>
                        <div style="font-size: 18px">Your order request could not be completed at this moment.</div>
                        <div style="font-size: 16px">It can be an issue relative to the payment information you provided to us. You can try to checkout
                            again and check your payment info carefully.</div>
                        <div style="font-size: 16px">If the problem persist, please feel free to
                            <a style="color: brown" href="/contact">
                                <b>contact us here</b>
                            </a> or call
                            <b>(+1) 281-954-1557</b>.</div>
                    </template>
                </div>
                <div class="tt-checkout">
                    <div class="row">
                        <div class="col-md-12 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Ordered products</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-right">
                                        <div v-if="current_order.discount_amount != null">Items Subtotal: ${{((current_order.total_amount*1 - current_order.total_tax_amount*1 - current_order.shipping_cost_estimated*1 + current_order.discount_amount*1)*1).toFixed(2)}}</div>
                                        <div v-else>Items Subtotal: ${{((current_order.total_amount*1 - current_order.total_tax_amount - current_order.shipping_cost_estimated*1)*1).toFixed(2)}}</div>
                                        <div v-if="current_order.discount_amount != null && current_order.discount_amount>0">Discount({{current_order.discount_code}}): ${{((current_order.discount_amount)*1).toFixed(2)}}</div>
                                        <div>Shipping & Handling: ${{((current_order.shipping_cost_estimated)*1).toFixed(2)}}</div>
                                        <div>Total Before Tax: ${{(current_order.subtotal-current_order.discount_amount+current_order.shipping_cost_estimated*1).toFixed(2)}}</div>
                                        <div>Taxes: ${{(current_order.total_tax_amount*1).toFixed(2)}}</div>
                                        <div v-if="confirmation_status == 'success'">
                                            <b>Total paid: ${{parseFloat(current_order.total_amount).toFixed(2)}}
                                            </b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                            <div class="tt-summary__products tt-summary--border" style="margin-right: 10px">
                                <ul>
                                    <li v-for="(cart_item, index) in current_order.order_items" :key="index">
                                        <div>
                                            <a href="#">
                                                <img :src="cart_item.item.files.length > 0 ? '/storage/' + cart_item.item.files[0].thumb_path : '/images/item-placeholder.jpg'"
                                                    alt="Image name">
                                            </a>
                                        </div>
                                        <div>
                                            <p>
                                                <a>{{cart_item.name}}</a>
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
                                                </span>
                                            </span>
                                            <div class="tt-summary__products_param-control tt-summary__products_param-control--open">
                                                <span>Details</span>
                                                <i class="icon-down-open"></i>
                                            </div>
                                            <div class="tt-summary__products_param">
                                                <span class="tt-summary__products_color">Description:
                                                    <span>{{cart_item.description}}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="row" style="margin-top: 40px">
                                    <div class="col-md-4">
                                        <a href="/items" class="tt-header__cart-viewcart btn" style="float: left">
                                            <i class="icon-shop24"></i> Continue Shopping</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a v-if="confirmation_status == 'error'" href="/cart/checkout" style="cursor: pointer" class="tt-header__cart-viewcart btn">
                                            Try checkout again
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a @click="cancelOrder()" style="cursor: pointer; float: right" class="tt-header__cart-viewcart btn">
                                            <i class="icon-cancel24"></i> Cancel order</a>
                                    </div>
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
    import Helpers from '../../../../../misc/helpers.js';
    export default {
        mixins: [CartServices, Helpers],
        props: ['order', 'payment_error', 'user', 'status'],
        data() {
            return {
                current_order: {},
                current_payment_error: {},
                confirmation_status: '',
            }
        },
        methods: {
            cancelOrder() {
                var self = this;
                if (!self.user) {
                    swal({
                        title: 'Order cancellation',
                        input: 'email',
                        buttons: {
                            cancel: {
                                text: "Cancel",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: true,
                            },
                            confirm: {
                                text: "OK",
                                value: true,
                                visible: true,
                                className: "",
                                closeModal: true
                            }
                        },
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your email",
                                type: "email",
                            }
                        }
                    }).then((email) => {
                        if (Helpers.validateEmail(email)) {
                            $.LoadingOverlay("show");
                            self.cancelOrderCall(self.current_order.id, email, self.cancelOrderCallback);
                        } else {
                            swal("Error!", 'Email addres is incorrect', "error");
                        }
                    })
                } else {
                    swal({
                        title: "Order cancellation",
                        text: "Are you sure you want to cancel this order?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: false,
                    }).then((confirm) => {
                        if (confirm) {
                            $.LoadingOverlay("show");
                            self.cancelOrderCall(self.current_order.id, '', self.cancelOrderCallback);
                        }
                    });
                }
            },
            cancelOrderCallback(code, data) {
                $.LoadingOverlay("hide");
                if (code == '200') {
                    toastr.success('Order cancelled successfully', 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                    window.location.href = '/items';
                } else if ('001') {
                    var errors  = '';
                    for(var i in data){
                        errors += data[i];
                    }
                    swal("Error!", errors, "error");
                } else if ('002') {
                    swal("Error!", data.error, "error");
                }
            },
            round(value, decimals) {
                return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
            },
            discounted_price(price, discount_percent) {
                var discounted_price = price - ((price * discount_percent) / 100);
                return parseFloat(discounted_price).toFixed(2);
            },
        },
        created() {
            if (this.order) {
                this.current_order = this.order;
                this.confirmation_status = 'success';
            }
            
            if (this.payment_error) {
                this.current_payment_error = this.payment_error;
                this.confirmation_status = 'error';
            }
        },
        mounted() {
            $.LoadingOverlay("hide");
            console.log('Component mounted.');
        }
    }
</script>
<style>
    .tt-counter.tt-counter__inner {
        width: 110px;
    }
</style>