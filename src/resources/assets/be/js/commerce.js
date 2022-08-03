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

//Dashboard
Vue.component('dashboard', require('./components/dashboard/DashboardComponent.vue'));

//Items
Vue.component('items-list', require('./components/items/ItemsListComponent.vue'));
Vue.component('items-create', require('./components/items/ItemsCreateComponent.vue'));

 //Categories
Vue.component('categories-list', require('./components/categories/CategoriesListComponent.vue'));
Vue.component('categories-create', require('./components/categories/CategoriesCreateComponent.vue'));

 //Categories
Vue.component('orders-list', require('./components/orders/OrdersListComponent.vue'));
Vue.component('orders-create', require('./components/orders/OrdersCreateComponent.vue'));

//Reports
Vue.component('reports-container', require('./components/reports/ReportsContainer.vue'));
Vue.component('reports-sales', require('./components/reports/ReportsSales.vue'));
Vue.component('reports-customer', require('./components/reports/ReportsCustomers.vue'));
Vue.component('reports-subscribers', require('./components/reports/ReportsSubscribers.vue'));
Vue.component('sales-date-range', require('./components/reports/SalesDateRange.vue'));
Vue.component('reports-activities-log', require('./components/reports/ReportsActivitiesLog.vue'));
Vue.component('products-sold', require('./components/reports/ReportProductsSoldDateRange.vue'));

//Discounts
Vue.component('discounts-list', require('./components/discounts/DiscountsListComponent.vue'));
Vue.component('discounts-create', require('./components/discounts/DiscountsCreateComponent.vue'));

//Settings
Vue.component('settings-container', require('./components/settings/SettingsContainer.vue'));
Vue.component('shipping-methods', require('./components/settings/ShippingMethods.vue'));
Vue.component('set-invoicing', require('./components/settings/InvoicingComponent.vue'));
Vue.component('taxes', require('./components/settings/TaxesComponent.vue'));
Vue.component('general', require('./components/settings/GeneralComponent.vue'));
Vue.component('set-inventory', require('./components/settings/InventoryComponent.vue'));
Vue.component('set-shipping', require('./components/settings/ShippingComponent.vue'));
Vue.component('set-communication', require('./components/settings/CommunicationComponent.vue'));

//Plans
Vue.component('plans-list', require('./components/plans/PlansListComponent.vue'));
Vue.component('plans-create', require('./components/plans/PlanCreateComponent.vue'));

Vue.component('subscriptions-list', require('./components/subscriptions/SubscriptionsListComponent.vue'));
Vue.component('subscriptions-create', require('./components/subscriptions/SubscriptionCreateComponent.vue'));

//Payment History
Vue.component('payment-history', require('./components/payments/PaymentHistoryComponent.vue'));
Vue.component('payment-container', require('./components/payments/PaymentContainerComponent.vue'));

//Collections
Vue.component('collections-list', require('./components/collections/CollectionsListComponent.vue'));
Vue.component('collections-create', require('./components/collections/CollectionsCreateComponent.vue'));

const commerce = new Vue({
    el: '#commerce-module'
});