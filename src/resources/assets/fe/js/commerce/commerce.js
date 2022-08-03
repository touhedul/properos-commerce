/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Cart
Vue.component('cart', require('./components/Cart.vue'));
Vue.component('checkout', require('./components/Checkout.vue'));
Vue.component('order-confirm', require('./components/OrderConfirmation.vue'));

Vue.component('my-account', require('./components/MyAccount.vue'));
Vue.component('plans', require('./components/PlansComponent.vue'));

Vue.component('products-index', require('./components/items/ProductsIndex.vue'));
Vue.component('products-list', require('./components/items/ProductsList.vue'));
Vue.component('product-details', require('./components/items/ProductDetails.vue'));

const commerce = new Vue({
    el: '#commerce-module'
});
