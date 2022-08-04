<?php

use Illuminate\Support\Arr;

Route::group([config('properos_commerce.router.default.options'), 'middleware' => 'web'], function(){

    $middleware = config('properos_commerce.router.default.middleware');
    $namespace = 'Properos\Commerce\Controllers';


    Route::get('/', $namespace.'\HomeController@homePage');

    Route::get('/about', $namespace.'\HomeController@about');
    Route::get('/how-it-works', $namespace.'\HomeController@howTo');
    Route::get('/terms', $namespace.'\HomeController@terms');
    Route::get('/privacy', $namespace.'\HomeController@privacy');
    Route::get('/contact', $namespace.'\HomeController@contact');
    Route::get('/our-story', $namespace.'\HomeController@ourStory');
    Route::get('/our-principles', $namespace.'\HomeController@ourPrinciples');
    Route::get('/help', $namespace.'\HomeController@help');
    // Route::get('/faq', $namespace.'\HomeController@faq');
    Route::get('/resources', $namespace.'\HomeController@resources');
    Route::get('/shipping-return-policy', $namespace.'\HomeController@shippingReturnPolicy');
    Route::get('/site-map', $namespace.'\HomeController@siteMap');
    Route::get('/engage-us', $namespace.'\HomeController@engageUs');
    Route::post('store/send-contact-email', $namespace.'\HomeController@sendContactEmail');
    Route::post('store/subscribe', $namespace.'\HomeController@subscribe');
    Route::get('/announcement', $namespace.'\HomeController@announcement');
    Route::get('/cookie/remove/{serial}', $namespace.'\HomeController@cookieRemove');

    //Wishlist
    Route::group(['prefix' => 'api/wishlist'], function () use($namespace) {
        Route::get('/get', $namespace.'\Wishlist\ApiWishlistController@get');
        Route::get('/count', $namespace.'\Wishlist\ApiWishlistController@getCount');
        Route::get('/set/{item}', $namespace.'\Wishlist\ApiWishlistController@set');
        Route::get('/remove/{item}', $namespace.'\Wishlist\ApiWishlistController@remove');
        Route::get('/removeall', $namespace.'\Wishlist\ApiWishlistController@removeAll');
        Route::get('/', $namespace.'\Wishlist\WishlistController@wishlistPage');
    });

    Route::group(['prefix' => 'wishlist'], function () use($namespace) {
        Route::get('/', $namespace.'\Wishlist\WishlistController@wishlistPage');
    });

    //Shopping-Cart
    Route::group(['prefix' => 'api/cart'], function ()  use($namespace) { 
        Route::get('/get', $namespace.'\Cart\ApiCartController@get');
        Route::get('/count', $namespace.'\Cart\ApiCartController@getCount');
        Route::get('/set/{item}/{qty}', $namespace.'\Cart\ApiCartController@setItem');
        Route::post('/set/{item}/{qty}', $namespace.'\Cart\ApiCartController@set');
        Route::get('/changeQty/{item}/{qty?}', $namespace.'\Cart\ApiCartController@changeQty');
        Route::get('/increase/{item}', $namespace.'\Cart\ApiCartController@increase');
        Route::get('/decrease/{item}', $namespace.'\Cart\ApiCartController@decrease');
        Route::get('/remove/{item}', $namespace.'\Cart\ApiCartController@remove');
        Route::get('/removeall', $namespace.'\Cart\ApiCartController@removeAll');
        Route::post('/shipping-rates/{carrier}', $namespace.'\Cart\ApiCartController@shippingRates');
        /* Route::get('/add-item/{item}/{qty}', $namespace.'\Cart\ApiCartController@addItem'); */
    });

    Route::group(['prefix' => 'cart'], function () use($namespace) {
        Route::get('/', $namespace.'\Cart\CartController@cartPage');
        Route::get('/checkout', $namespace.'\Cart\CartController@checkout');
    });

    //Items
    Route::group(['prefix' => 'items'], function () use($namespace) {
        Route::get('/', $namespace.'\Item\ItemController@items');
        Route::get('/collection/{slug}', $namespace.'\Item\ItemController@itemsCollection');
        Route::get('/category/{category}', $namespace.'\Item\ItemController@categoryItems');
        Route::get('/{item}', $namespace.'\Item\ItemController@showItem');
    });

    //Api Items
    Route::group(['prefix' => 'api/items', 'middleware' => Arr::get($middleware, 'api/items', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('index', $namespace.'\Item\ApiItemController@index');
        Route::get('create', $namespace.'\Item\ApiItemController@create');
        Route::post('store', $namespace.'\Item\ApiItemController@store');
        Route::get('show/{id}', $namespace.'\Item\ApiItemController@show');
        Route::get('edit/{id}', $namespace.'\Item\ApiItemController@edit');
        Route::put('update/{id}', $namespace.'\Item\ApiItemController@update');
        Route::delete('destroy/{id}', $namespace.'\Item\ApiItemController@destroy');
        Route::post('marketplace-link', $namespace.'\Item\ApiItemController@marketplaceLink');
        Route::delete('marketplace-remove/{id}', $namespace.'\Item\ApiItemController@marketplaceRemove');
        Route::get('activate/{id}', $namespace.'\Item\ApiItemController@activate');
    });

    //Api Collection
    Route::group(['prefix' => 'api/collections', 'middleware' => Arr::get($middleware, 'api/collection', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::post('search', $namespace.'\Collection\ApiCollectionController@search');
        Route::post('create', $namespace.'\Collection\ApiCollectionController@create');
        Route::post('update', $namespace.'\Collection\ApiCollectionController@update');
        Route::delete('delete/{id}', $namespace.'\Collection\ApiCollectionController@delete');
    });

    Route::group(['prefix' => 'api/items'], function () use($namespace) {
        Route::get('/', $namespace.'\Item\ApiItemController@items');
        Route::get('/{slug}', $namespace.'\Item\ApiItemController@itemsCollection');
    });

    Route::group(['prefix' => 'api/items/filter'], function () use($namespace) {
        // Route::get('category/{ids}/{min?}/{max?}', $namespace.'\Item\ApiItemController@filterByCategory');
        // Route::get('price-range/{min}/{max}/{ids?}', $namespace.'\Item\ApiItemController@filterByPriceRange');
        // Route::get('collection/category/{collection_id}/{ids}/{min?}/{max?}', $namespace.'\Item\ApiItemController@filterByCollectionCategory');
        Route::get('search/{string?}', $namespace.'\Item\ApiItemController@searchByString');
        Route::post('search-items', $namespace.'\Item\ApiItemController@searchItems');
    });

    //Api Reviews
    Route::group(['prefix' => 'api/reviews'], function () use($namespace) {
        Route::post('store', $namespace.'\Item\ReviewController@store');
    });
    Route::get('api/reviews/get/{item_id}', $namespace.'\Item\ReviewController@getReviews');


    //Categories
    Route::group(['prefix' => 'api/categories', 'middleware' => Arr::get($middleware, 'api/categories', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('index', $namespace.'\Category\ApiCategoryController@index');
        Route::get('create', $namespace.'\Category\ApiCategoryController@create');
        Route::post('store', $namespace.'\Category\ApiCategoryController@store');
        Route::get('show/{id}', $namespace.'\Category\ApiCategoryController@show');
        Route::get('edit/{id}', $namespace.'\Category\ApiCategoryController@edit');
        Route::put('update/{id}', $namespace.'\Category\ApiCategoryController@update');
        Route::delete('destroy/{id}', $namespace.'\Category\ApiCategoryController@destroy');
    });

    //Orders
    Route::group(['prefix' => 'orders', 'middleware' => Arr::get($middleware, 'public', [])], function () use($namespace) {
        Route::get('order-confirmation/{id}/{status}', $namespace.'\Order\OrderController@getOrderConfirmation');
        Route::get('/invoice/{order_number}', $namespace.'\Order\OrderController@openInvoice');
        Route::get('/quote/{order_number}', $namespace.'\Order\OrderController@openQuote');
        Route::get('/packing-slip/{order_number}', $namespace.'\Order\OrderController@openPackingSlip');
    });
    Route::get('/invoice/{order_number}', $namespace.'\Order\OrderController@publicInvoice');

    Route::group(['prefix' => 'orders', 'middleware' => Arr::get($middleware, 'orders', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('tracking/{carrier}/{number}', $namespace.'\Order\OrderController@trackingOrder');
    });

    Route::group(['prefix' => 'api/orders', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('index', $namespace.'\Order\ApiOrderController@index');
        Route::get('create', $namespace.'\Order\ApiOrderController@create');
        Route::post('store', $namespace.'\Order\ApiOrderController@store');
        Route::post('place-order', $namespace.'\Order\ApiOrderController@placeOrder');
        Route::get('show/{id}', $namespace.'\Order\ApiOrderController@show');
        Route::get('edit/{id}', $namespace.'\Order\ApiOrderController@edit');
        Route::put('update/{id}', $namespace.'\Order\ApiOrderController@update');
        Route::delete('destroy/{id}', $namespace.'\Order\ApiOrderController@destroy');
        Route::get('user-orders', $namespace.'\Order\ApiOrderController@userOrders');
        Route::get('tracking/{carrier}/{number}', $namespace.'\Order\ApiOrderController@trackingOrder');
        Route::post('/shipping-info/{id}', $namespace.'\Order\ApiOrderController@getShippingInfo');
        Route::post('/send-invoice-email/{id}', $namespace.'\Order\ApiOrderController@sendInvoiceByEmail');
        Route::post('/send-quote-email/{id}', $namespace.'\Order\ApiOrderController@sendQuoteByEmail');
        
        
    });

    Route::group(['prefix' => 'api/guest/orders'], function () use($namespace) {
        Route::post('place-order', $namespace.'\Order\ApiOrderController@placeOrder');
        Route::get('cancel/{id}/{email?}', $namespace.'\Order\ApiOrderController@cancel');
        Route::post('process-invoice-payment', $namespace.'\Order\ApiOrderController@processInvoicePayment');
    });

    //OrdersItems
    Route::group(['prefix' => 'api/ordersItems', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('index', $namespace.'\OrderItem\ApiOrderItemController@index');
        Route::get('create', $namespace.'\OrderItem\ApiOrderItemController@create');
        Route::post('store', $namespace.'\OrderItem\ApiOrderItemController@store');
        Route::get('show/{id}', $namespace.'\OrderItem\ApiOrderItemController@show');
        Route::get('edit/{id}', $namespace.'\OrderItem\ApiOrderItemController@edit');
        Route::put('update/{id}', $namespace.'\OrderItem\ApiOrderItemController@update');
        Route::delete('destroy/{id}', $namespace.'\OrderItem\ApiOrderItemController@destroy');
    });

    //Discounts
    Route::post('/discounts/check/{code}', $namespace.'\Discount\DiscountController@checkCode');
    Route::group(['prefix' => 'api/discounts', 'middleware' => 'auth'], function () use($namespace) {
        Route::post('store', $namespace.'\Discount\DiscountController@store');
        Route::put('{id}/update', $namespace.'\Discount\DiscountController@update');
        Route::delete('destroy/{id}', $namespace.'\Discount\DiscountController@destroy');
    });


    Route::group(['prefix' => 'api/admin', 'middleware' => ['auth', 'role:admin']], function () use($namespace) {
        Route::post('add-customer', $namespace.'\Admin\ApiAdminController@addCustomer');
        Route::get('orders', $namespace.'\Admin\ApiAdminController@orders');
        Route::get('recent-orders', $namespace.'\Admin\ApiAdminController@recentOrders');
        Route::get('items', $namespace.'\Admin\ApiAdminController@items');
        Route::get('categories', $namespace.'\Admin\ApiAdminController@categories');
        Route::post('/categories/search', $namespace.'\Admin\ApiAdminController@categoriesSearch');
        Route::post('/items/search', $namespace.'\Admin\ApiAdminController@itemsSearch');
        Route::post('/orders/search', $namespace.'\Admin\ApiAdminController@ordersSearch');
        Route::post('/discounts/search', $namespace.'\Admin\ApiAdminController@discountsSearch');
        Route::post('/locations/search', $namespace.'\Admin\ApiAdminController@locationSearch');
        Route::post('/get-users', $namespace.'\Admin\ApiAdminController@usersSearch');
        Route::post('/reports/sales-date-range', $namespace.'\Admin\ApiAdminController@saleDateRange');
        Route::post('/reports/products-sold-date-range', $namespace.'\Admin\ApiAdminController@productsSoldDateRange');
        Route::post('/subscribers/search', $namespace.'\Admin\ApiAdminController@subscribersSearch');
        Route::post('/carrier-services/{carrier}', $namespace.'\Admin\ApiAdminController@getCarrierServices');
        Route::post('/store/settings',  $namespace.'\Admin\SettingsController@store');
        Route::post('/get-tax',  $namespace.'\Admin\ApiAdminController@getTax');
        Route::post('/taxes/store',  $namespace.'\Admin\ApiAdminController@storeTaxes');
        Route::post('/taxes/update',  $namespace.'\Admin\ApiAdminController@updateTaxes');
        Route::post('/taxes/search', $namespace.'\Admin\ApiAdminController@taxesSearch');
        Route::get('/taxes/{id}/delete', $namespace.'\Admin\ApiAdminController@deleteTax');
        Route::post('/packages/store',  $namespace.'\Admin\ApiAdminController@storePackage');
        Route::post('/packages/update',  $namespace.'\Admin\ApiAdminController@updatePackage');
        Route::delete('/packages/{id}/delete',  $namespace.'\Admin\ApiAdminController@deletePackage');
        Route::post('/packages/search',  $namespace.'\Admin\ApiAdminController@packageSearch');

        Route::post('/options/search',  $namespace.'\Admin\ApiAdminController@optionsSearch');
        Route::delete('/options/{id}/delete',  $namespace.'\Admin\ApiAdminController@deleteOption');
        
        Route::post('/activities-log/search', $namespace.'\Admin\ApiAdminController@activitiesLogSearch');

        Route::post('/payment/add-payment-method', $namespace.'\Payment\ApiPaymentController@addPaymentMethod');
        Route::post('/payment/remove-payment-profile', $namespace.'\Payment\ApiPaymentController@removePaymentMethod');
        Route::post('/payment/pay-with-profile', $namespace.'\Payment\ApiPaymentController@payWithProfile');
        Route::post('/payment/paid-with-cash', $namespace.'\Payment\ApiPaymentController@payWithCash');
        Route::post('/payment/set-default-payment_method', $namespace.'\Payment\ApiPaymentController@setDefaultPayment');
        Route::post('/payment/refund-payment', $namespace.'\Payment\ApiPaymentController@refundPayment');
        Route::post('/payment/void-payment', $namespace.'\Payment\ApiPaymentController@voidPayment');
        Route::post('/payment/search', $namespace.'\Payment\ApiPaymentController@search');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () use($namespace) {
        Route::get('/dashboard', $namespace.'\Admin\AdminController@index');
        Route::group(['prefix' => 'store', 'middleware' => ['auth', 'role:admin']], function () use($namespace) {
            Route::get('/restore', $namespace.'\Admin\AdminController@restore');
            //Items
            Route::get('/items', $namespace.'\Admin\AdminController@items');
            Route::get('/show-item/{id}', $namespace.'\Admin\AdminController@showItem');
            Route::get('/add-item', $namespace.'\Admin\AdminController@createItem'); 
            Route::get('/edit-item/{id}', $namespace.'\Admin\AdminController@editItem');
            
            //Reports
            Route::get('/reports', $namespace.'\Admin\AdminController@reports');
            Route::get('/reports/sales', $namespace.'\Admin\AdminController@reportSales');
            Route::get('/reports/customers', $namespace.'\Admin\AdminController@reportCustomers');
            Route::get('/reports/subscribers', $namespace.'\Admin\AdminController@reportSubscribers');
            Route::get('/reports/activities-log', $namespace.'\Admin\AdminController@reportActivitiesLog');
            Route::get('/reports/products-sold', $namespace.'\Admin\AdminController@reportProductsSold');
    
            //Categories
            Route::get('/categories', $namespace.'\Admin\AdminController@categories');
            Route::get('/add-category', $namespace.'\Admin\AdminController@createCategory');
            Route::get('/edit-category/{id}', $namespace.'\Admin\AdminController@editCategory');
    
            //Orders
            Route::get('/orders', $namespace.'\Admin\AdminController@orders');
            Route::get('/show-order/{id}', $namespace.'\Admin\AdminController@showOrder');
            Route::get('/add-order', $namespace.'\Admin\AdminController@createOrder');
            Route::get('/edit-order/{id}', $namespace.'\Admin\AdminController@editOrder');
            Route::get('/order/print-label/{order_id}', $namespace.'\Admin\AdminController@printOrderLabel');
    
            //Discounts
            Route::get('/discounts', $namespace.'\Admin\AdminController@discounts');
            Route::get('/add-discount', $namespace.'\Admin\AdminController@createDiscount');
            Route::get('/edit-discount/{id}', $namespace.'\Admin\AdminController@editDiscount');
    
            //Settings
            Route::get('/settings', $namespace.'\Admin\AdminController@settings');
    
            //Plans
            Route::get('/plans', $namespace.'\Admin\AdminController@plans');
            Route::get('/add-plan', $namespace.'\Admin\AdminController@createPlan');
            Route::get('/edit-plan/{id}', $namespace.'\Admin\AdminController@editPlan');
    
            //Subscription
            Route::get('/subscriptions', $namespace.'\Admin\AdminController@subscriptions');
            Route::get('/add-subscription', $namespace.'\Admin\AdminController@createSubscription');
            Route::get('/edit-subscription/{id}', $namespace.'\Admin\AdminController@editSubscription');

            //Collections
            Route::get('/collections', $namespace.'\Collection\CollectionController@index');
            Route::get('/add-collection', $namespace.'\Collection\CollectionController@create'); 
            Route::get('/edit-collection/{id}', $namespace.'\Collection\CollectionController@edit');

        });
        Route::group(['prefix' => 'settings', 'middleware' => ['auth', 'role:admin']], function () use($namespace) {
            Route::get('/general', $namespace.'\Admin\SettingsController@general');
            Route::get('/invoicing', $namespace.'\Admin\SettingsController@invoicing');
            Route::get('/taxes', $namespace.'\Admin\SettingsController@taxes');
            Route::get('/inventory', $namespace.'\Admin\SettingsController@inventory');
            Route::get('/shipping', $namespace.'\Admin\SettingsController@shipping');
            Route::get('/communications-page', $namespace.'\Admin\SettingsController@communicationsPage');
        });
        Route::group(['prefix' => 'orders', 'middleware' => ['auth', 'role:admin']], function () use($namespace){
            Route::get('/{id}/payment-history', $namespace.'\Admin\AdminController@paymentHistory');
            Route::get('/{user_id}/{order_id}/configure-payment', $namespace.'\Admin\AdminController@configurePayment');
        });
    });
    //Settings
    Route::group(['prefix' => 'api/settings', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('/homepage', $namespace.'\Admin\SettingsController@homepageSettings');
    });
    //Files
    Route::group(['prefix' => 'api/files', 'middleware' => 'auth'], function () use($namespace) {
        Route::post('upload', $namespace.'\File\ApiFileController@upload');
        Route::delete('destroy/{id}', $namespace.'\File\ApiFileController@deleteFile');
    });

    //Amazon Integration
    Route::group(['prefix' => 'amazon'], function () use($namespace) {
        Route::get('/get-products', $namespace.'\Integrations\ECommerce\ECommerceController@getAmazonProducts');
    });

    //Ecommerce test
    Route::get('/pending-orders/{marketplace}', $namespace.'\Integrations\Ecommerce\ECommerceController@getOrders');
    Route::get('/order-items/{id}', $namespace.'\Integrations\Ecommerce\ECommerceController@getOrdersItems');
    Route::get('/update-qty', $namespace.'\Integrations\Ecommerce\ECommerceController@updateProductQtyFeed');
    Route::get('/update-qty-result/{id}', $namespace.'\Integrations\Ecommerce\ECommerceController@updateProductQtyFeedResult');

    //Plans
    Route::group(['prefix' => 'api/plans', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('/info/{id}', $namespace.'\Plan\ApiPlanController@getInfoPlan');
        Route::post('/search', $namespace.'\Plan\ApiPlanController@search');
        Route::post('/store', $namespace.'\Plan\ApiPlanController@store');
        Route::post('/update/{id}', $namespace.'\Plan\ApiPlanController@update');
        Route::delete('/remove/{id}', $namespace.'\Plan\ApiPlanController@destroy');
    });

    //Membership
    Route::group(['prefix' => 'api/subscriptions', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('/', $namespace.'\Subscription\ApiSubscriptionController@show');
        Route::post('/search', $namespace.'\Subscription\ApiSubscriptionController@search');
        Route::post('/store', $namespace.'\Subscription\ApiSubscriptionController@store');
        Route::post('/save', $namespace.'\Subscription\ApiSubscriptionController@createSubscription');
        Route::match(['put', 'post'], '/change', $namespace.'\Subscription\ApiSubscriptionController@change');
        Route::match(['put', 'post'], '/update', $namespace.'\Subscription\ApiSubscriptionController@update');
        Route::delete('/remove/{id}', $namespace.'\Subscription\ApiSubscriptionController@remove');
    });

    Route::get('/membership/plans', $namespace.'\Plan\PlanController@show');

    //Account
    Route::group(['prefix' => 'my-account', 'middleware' => 'auth'], function () use($namespace) {
        Route::get('/', $namespace.'\Account\AccountController@index');
    });
    Route::group(['prefix' => 'api/my-account', 'middleware' => 'auth'], function () use($namespace) {
        Route::post('/update-profile', $namespace.'\Account\ApiAccountController@updateProfile');
        Route::get('/basic-user-info/{id}', $namespace.'\Account\ApiAccountController@getBasicUserInfo');
        Route::post('/add-address', $namespace.'\Account\ApiAccountController@addAddress');
        Route::put('/update-address/{id}', $namespace.'\Account\ApiAccountController@updateAddress');
        Route::get('/set-default/{id}', $namespace.'\Account\ApiAccountController@setDefaultAddress');
        Route::get('/remove-address/{id}', $namespace.'\Account\ApiAccountController@removeAddress');
        Route::post('/change-password', $namespace.'\Account\ApiAccountController@changePassword');
        Route::post('/add-payment-method', $namespace.'\Account\ApiAccountController@addPaymentMethod');
        Route::get('/get-payment-method/{id}', $namespace.'\Account\ApiAccountController@getPaymentMethod');
        Route::delete('/remove-payment-method/{id}', $namespace.'\Account\ApiAccountController@removePaymentMethod');
    });

    //Store
    Route::group(['prefix' => 'api/store'], function () use($namespace) {
        Route::get('/get-countries', $namespace.'\Location\ApiLocationController@getCountries');
        Route::get('/get-states', $namespace.'\Location\ApiLocationController@getStates');
        Route::post('/locations/store',  $namespace.'\Admin\ApiAdminController@storeLocations');
        Route::post('/locations/update',  $namespace.'\Admin\ApiAdminController@updateLocations');
        Route::get('/locations/{id}/delete', $namespace.'\Admin\ApiAdminController@deleteLocation');
    });

    Route::group(['prefix' => 'api/payment'], function () use($namespace) {
        Route::match(['get', 'post'], 'success', $namespace.'\Payment\ApiPaymentController@payed');
        Route::match(['get', 'post'], 'cancel', $namespace.'\Payment\ApiPaymentController@cancelPayed');
        Route::match(['post', 'get'], 'paypal/listener', $namespace.'\Payment\ApiPaymentController@payed');
        Route::get('/methods', $namespace.'\Payment\ApiPaymentController@getPaymentsMethods')->middleware('auth');
    });

    Route::group(['prefix' => 'api/shipping', 'middleware' => 'auth'], function () use($namespace) {
        Route::post('/shipping-info', $namespace.'\Integrations\Shipping\ApiShippingController@getShippingfInfo');
    });

});


