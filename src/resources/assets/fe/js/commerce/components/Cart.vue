<template>
    <div class="tt-layout tt-sticky-block__parent ">
        <div class="tt-layout__content">
            <div class="container">
                <div class="tt-page__breadcrumbs" style="margin-bottom:  0px">
                    <ul class="tt-breadcrumbs">
                        <li>
                            <a href="/">
                                <i class="icon-home"></i>
                            </a>
                        </li>
                        <li>
                            <span>Cart</span>
                        </li>
                    </ul>
                </div>
                <div v-if="current_items.length > 0" class="row">
                    <div class="col-md-10 offset-md-1">
                        <h4>Shopping Cart</h4>
                    </div>
                </div>
                <div v-if="current_items.length > 0" class="row">
                    <div class="col-md-10 offset-md-1">
                        <div v-if="!user" style="font-size: 16px">You're not currently signed. In order to save this items and see those saved previously...
                            <a href="/login">sign in here</a>
                        </div>
                    </div>
                </div>
                <div v-if="current_items.length > 0" class="tt-cart" style="margin-top: 15px">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="tt-cart__caption">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span>Products</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span>Price</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span>Quantity</span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span>Product Total</span>
                                    </div>
                                    <div class="col-md-1 text-center">

                                    </div>
                                </div>
                            </div>
                            <div class="tt-cart__list">
                                <div v-for="(item, key) in current_items" class="tt-cart__product" :id="'item_' + item.id" :key="key">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <a :href="'/items/'+item.sku" class="tt-cart__product_image">
                                                <img :src="item.thumb_path ? '/storage/' + item.thumb_path : '/images/item-placeholder.jpg'" alt="Image name">
                                            </a>
                                            <div class="tt-cart__product_info">
                                                <a :href="'/items/'+item.sku">
                                                    <p>{{item.name}} <span v-if="item.options && !Array.isArray(item.options)"><br>
                                                        (<span v-for="(opt, index, i) in item.options" :key="index" style="text-transform:capitalize;font-size:12px;">
                                                            {{paintOptions(index, opt, i)}}
                                                        </span>)
                                                    </span></p>
                                                    <br>
                                                    <span style="color: rgb(180, 11, 11); font-weight: bold; font-size: 13px" v-if="item.discount_percent">
                                                        <i class="icon-certificate-1" style="color: rgb(180, 11, 11)"></i>
                                                        {{item.discount_percent}}% off applied
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-4 text-center">
                                            <div class="tt-cart__product_price">
                                                <span v-if="item.discount_percent">
                                                    ${{discounted_price(item.price, item.discount_percent)}}
                                                </span>
                                                <span v-else>
                                                    ${{parseFloat(item.price).toFixed(2)}}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-3 text-center">
                                            <div class="tt-counter tt-counter__inner" data-min="1" data-max="999">
                                                <input :id="item.id" v-model="item.qty" type="text" :data-item_id="item.id" @keyup.enter="changeQty(item.id, item.qty, item.total_qty)"
                                                    @focusout="changeQty(item.id, item.qty, item.total_qty)" class="form-control">
                                                <div class="tt-counter__control">
                                                    <span @click="increase(item.id, item.qty, item.total_qty)" class="icon-up-circle" :class="{'disable_span' : errors.max_reached, 'enable_span' : !errors.max_reached}"
                                                        data-direction="next"></span>
                                                    <span @click="decrease(item.id, item.qty, item.total_qty)" class="icon-down-circle" data-direction="prev"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <div style="color: red; font-size: 12px" v-if="errors.max_reached" data-id="item.id">{{errors_msg.max_reached}}</div>
                                        </div>

                                        <div class="col-md-2 col-4 text-center">
                                            <div class="tt-cart__product_price">
                                                <div class="tt-price">
                                                    <span v-if="item.discount_percent">
                                                        ${{discounted_product_total(item.price, item.discount_percent, item.qty)}}
                                                    </span>
                                                    <span v-else>
                                                        ${{product_total(item.price, item.qty)}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-1 text-center">
                                            <div class="tt-cart__product_price">
                                                <div class="tt-price">
                                                    <i @click="removeItem(item.id)" class="icon-trash" style="cursor:pointer;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tt-summary">
                                <div class="tt-summary--border">
                                    <h4>Cart summary</h4>
                                </div>
                                <div class="tt-summary--border">
                                    <div class="tt-summary__total ttg-mb--20">
                                        <p>Subtotal:
                                            <span style="color: dimgray">${{parseFloat(subtotal).toFixed(2)}}</span>
                                        </p>
                                    </div>
                                </div>
                                <a href="/cart/checkout" class="tt-summary__btn-checkout btn btn-type--icon colorize-btn6">
                                    <i class="icon-check"></i>
                                    <span>Proceed to Checkout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="tt-cart__list">
                    <div class="tt-empty" style="padding-bottom:  0px; margin-bottom:  120px; margin-top: 0px">
                        <i class="tt-empty__icon">
                            <img src="/images/empty-shopping-cart.svg" alt="Image name">
                        </i>
                        <div class="tt-page__name text-center">
                            <h1>Shopping Cart is Empty</h1>
                            <p>You have no items in your shopping cart.</p>
                        </div>
                        <div class="tt-empty__btn">
                            <a href="/items" class="btn">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CartServices from '../services/CartServices.js'
    export default {
        mixins: [CartServices],
        props: ['items', 'user'],
        data() {
            return {
                current_items: [],
                subtotal: 0.00,

                errors: {
                    max_reached: false
                },
                errors_msg: {
                    max_reached: ''
                }
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
            increase(item_id, qty, total_qty) {
                if (qty >= 1 && qty < total_qty) {
                    $.LoadingOverlay("show");
                    this.changeQtyCall(item_id, parseInt(qty) + 1, this.changeQtyHandler);
                } else {
                    this.errors.max_reached = true;
                    this.errors_msg.max_reached = "Quantity must be less or equal than " + total_qty;
                }

            },
            decrease(item_id, qty, total_qty) {
                this.errors.max_reached = false;
                if (qty >= 1 && qty <= total_qty) {
                    $.LoadingOverlay("show");
                    this.changeQtyCall(item_id, parseInt(qty) - 1, this.changeQtyHandler);
                } else {
                    this.errors.max_reached = true;
                    this.errors_msg.max_reached = "Quantity must be less or equal than " + total_qty;
                }

            },
            changeQty(item_id, qty, total_qty) {
                if (qty >= 1 && qty <= total_qty) {
                    $.LoadingOverlay("show");
                    this.changeQtyCall(item_id, parseInt(qty), this.changeQtyHandler);
                } else {
                    this.errors.max_reached = true;
                    this.errors_msg.max_reached = "Quantity must be less or equal than " + total_qty;
                }
            },
            changeQtyHandler(code, data) {
                $.LoadingOverlay("hide");
                this.current_items = [];
                this.fillCartItem(data.cart)
                paintCart();
            },
            getSubTotal() {
                this.subtotal = 0.00;
                if (this.current_items.length > 0) {

                }
                for (var i in this.current_items) {
                    if (this.current_items[i].discount_percent == null) {
                        this.subtotal = this.subtotal + (this.current_items[i].price * this.current_items[i].qty);
                    } else {
                        this.subtotal = this.subtotal + ((parseFloat(this.current_items[i].price).toFixed(2) - ((parseFloat(this.current_items[i].price).toFixed(2) * this.current_items[i].discount_percent) / 100)) * parseFloat(this.current_items[i].qty).toFixed(2));
                    }
                }
            },
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
            removeItem(item_id) {
                $.LoadingOverlay("show");
                this.removeItemCall(item_id, this.removeItemHandler);
            },
            removeItemHandler(code, data, item_id) {
                $.LoadingOverlay("hide");
                if (this.current_items.length > 0) {
                    for (var i in this.current_items) {
                        if (this.current_items[i].id == item_id) {
                            this.current_items.splice(i, 1);
                        }
                    }
                }
                paintCart();
                this.getSubTotal();
            },
            fillCartItem(data) {
                for (var property in data) {
                    if (data.hasOwnProperty(property)) {
                        this.current_items.push(data[property]);
                    }
                }
                this.getSubTotal();
            }
        },
        created() {
            if (this.items) {
                this.fillCartItem(this.items);
            }

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style>
    .tt-counter.tt-counter__inner {
        width: 110px;
    }

    .tt-summary {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .tt-cart__list {
        border-bottom: solid 0px;
        padding-bottom: 0px;
        margin-bottom: 10px;
    }

    .disable_span {
        pointer-events: none !important;
    }

    .enable_span {
        pointer-events: auto !important;
    }

    @media (max-width: 480px){
        .container {
            width: 750px;
            max-width: 100%;
        }
    }
</style>