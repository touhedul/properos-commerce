
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment'
Vue.prototype.moment = moment

import VueYouTubeEmbed from 'vue-youtube-embed';
Vue.use(VueYouTubeEmbed)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Product List
/* Vue.component('products-index', require('./components/front/ProductsIndex.vue')); */
/* Vue.component('products-list', require('./components/front/ProductsList.vue')); */
/* Vue.component('product-details', require('./components/front/ProductDetails.vue')); */


//Cart
Vue.component('cart', require('../components/Cart.vue'));
Vue.component('checkout', require('../components/Checkout.vue'));
Vue.component('order-confirm', require('../components/OrderConfirmation.vue'));

 //My-Account
 Vue.component('my-account', require('./components/front/MyAccount.vue'));
 Vue.component('plans', require('./components/membership/PlansComponent.vue'));


//import CartApiCalls from './components/apiCalls/cart-api-calls.js'


//Store-Specific
Vue.component('products-index', require('../components/items/ProductsIndex.vue'));
Vue.component('products-list', require('../components/items/ProductsList.vue'));
Vue.component('product-details', require('./components/items/ProductDetails.vue'));

const app = new Vue({
    el: '#app'
});
