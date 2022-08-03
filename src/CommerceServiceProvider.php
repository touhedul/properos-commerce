<?php

namespace Properos\Commerce;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class CommerceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'item' => 'Properos\Commerce\Models\Item',
            'category' => 'Properos\Commerce\Models\Category',
        ]);

        $this->publishes([
            __DIR__.'/properos_commerce.php' => config_path('properos_commerce.php'),
        ]);
        $this->publishes([
            __DIR__.'/shipping.php' => config_path('shipping.php'),
        ]);
        $this->publishes([
            __DIR__.'/amazon.php' => config_path('amazon.php'),
        ]);

        $this->publishes([
            __DIR__.'/invoice.php' => config_path('invoice.php'),
        ]);

       
        $this->loadRoutesFrom(__DIR__.'/commerce-routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->publishes([
            __DIR__.'/seeds' => base_path('database/seeds'),
        ]);

        $this->publishes([
            __DIR__.'/public/be/vendor' => public_path('/be/vendor/jquery'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/images/icons' => public_path('/images/icons'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/images' => public_path('/images'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/json' => public_path('/json'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/vendor' => public_path('/themes/default/vendor'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/ajax' => public_path('/themes/default/fe/ajax'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/api-calls' => public_path('/themes/default/fe/api-calls'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/css' => public_path('/themes/default/fe/css'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/fonts' => public_path('/themes/default/fe/fonts'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/images' => public_path('/themes/default/fe/images'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/js' => public_path('/themes/default/fe/js'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/media' => public_path('/themes/default/fe/media'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/fe/scss' => public_path('/themes/default/fe/scss'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/public/js' => public_path('/themes/default/js'),
        ], 'commerce');

        $this->publishes([
            __DIR__.'/resources/assets/be' => resource_path('assets/js/be/modules/commerce'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/fe/js/commerce' => resource_path('assets/js/fe/modules/commerce/js'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/fe/js/blog' => resource_path('assets/js/fe/modules/cms/js/components/blog'),
        ], 'commerce');

        $this->publishes([
            __DIR__.'/resources/assets/global/components/CardProcessorComponent.vue' => resource_path('assets/js/components/CardProcessorComponent.vue'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/components/BankProcessorComponent.vue' => resource_path('assets/js/components/BankProcessorComponent.vue'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/components/CashProcessorComponent.vue' => resource_path('assets/js/components/CashProcessorComponent.vue'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/components/AddressRadioButton.vue' => resource_path('assets/js/components/AddressRadioButton.vue'),
        ], 'commerce');

        $this->publishes([
            __DIR__.'/resources/assets/global/misc/authorize.js' => resource_path('assets/js/misc/authorize.js'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/misc/payments.js' => resource_path('assets/js/misc/payments.js'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/misc/stripe.js' => resource_path('assets/js/misc/stripe.js'),
        ], 'commerce');
        $this->publishes([
            __DIR__.'/resources/assets/global/services' => resource_path('assets/js/services'),
        ], 'commerce');

        $this->publishes([
            __DIR__.'/resources/views/be/commerce' => resource_path('views/be/commerce'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/reports' => resource_path('views/be/reports'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/settings' => resource_path('views/be/settings'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/layouts/menu' => resource_path('views/be/layouts/menu'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/dashboard.blade.php' => resource_path('views/be/index.blade.php'),
        ]);
        
        $this->publishes([
            __DIR__.'/resources/views/fe/cart.blade.php' => resource_path('views/themes/default/cart.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/checkout.blade.php' => resource_path('views/themes/default/checkout.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/item.blade.php' => resource_path('views/themes/default/item.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/items.blade.php' => resource_path('views/themes/default/items.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/my_account.blade.php' => resource_path('views/themes/default/my_account.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/order-confirm.blade.php' => resource_path('views/themes/default/order-confirm.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/plans.blade.php' => resource_path('views/themes/default/plans.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/wishlist.blade.php' => resource_path('views/themes/default/wishlist.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/index.blade.php' => resource_path('views/themes/default/index.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/blog.blade.php' => resource_path('views/themes/default/blog.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/post.blade.php' => resource_path('views/themes/default/post.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/about.blade.php' => resource_path('views/themes/default/about.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/contact.blade.php' => resource_path('views/themes/default/contact.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/engage-us.blade.php' => resource_path('views/themes/default/engage-us.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/faq.blade.php' => resource_path('views/themes/default/faq.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/help.blade.php' => resource_path('views/themes/default/help.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/how-to.blade.php' => resource_path('views/themes/default/how-to.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/our-principles.blade.php' => resource_path('views/themes/default/our-principles.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/our-story.blade.php' => resource_path('views/themes/default/our-story.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/privacy.blade.php' => resource_path('views/themes/default/privacy.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/shipping-return-policy.blade.php' => resource_path('views/themes/default/shipping-return-policy.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/terms.blade.php' => resource_path('views/themes/default/terms.blade.php'),
        ]);

        $this->publishes([
            __DIR__.'/resources/views/fe/emails' => resource_path('views/themes/default/emails'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/auth' => resource_path('views/themes/default/auth'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/pages' => resource_path('views/themes/default/layouts/pages'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/footer.blade.php' => resource_path('views/themes/default/layouts/footer.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/header.blade.php' => resource_path('views/themes/default/layouts/header.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/main.blade.php' => resource_path('views/themes/default/layouts/main.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/menu.blade.php' => resource_path('views/themes/default/layouts/menu.blade.php'),
        ]);

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Properos\Commerce\Classes\Integrations\Shipping\Common\IShippingUPS',
            'Properos\Commerce\Classes\Integrations\Shipping\UPS\ShippingUPS'
        );
        
        $this->app->make('Properos\Commerce\Controllers\HomeController');
        $this->app->make('Properos\Commerce\Controllers\Admin\AdminController');
        $this->app->make('Properos\Commerce\Controllers\Admin\ApiAdminController');
        $this->app->make('Properos\Commerce\Controllers\Cart\CartController');
        $this->app->make('Properos\Commerce\Controllers\Cart\ApiCartController');
        $this->app->make('Properos\Commerce\Controllers\Category\ApiCategoryController');
        $this->app->make('Properos\Commerce\Controllers\Discount\DiscountController');
        $this->app->make('Properos\Commerce\Controllers\File\ApiFileController');
        $this->app->make('Properos\Commerce\Controllers\Integrations\ECommerce\ECommerceController');
        $this->app->make('Properos\Commerce\Controllers\Integrations\Shipping\ShippingController');
        $this->app->make('Properos\Commerce\Controllers\Integrations\Shipping\ApiShippingController');
        $this->app->make('Properos\Commerce\Controllers\Item\ItemController');
        $this->app->make('Properos\Commerce\Controllers\Item\ApiItemController');
        $this->app->make('Properos\Commerce\Controllers\Item\ReviewController');
        $this->app->make('Properos\Commerce\Controllers\Order\OrderController');
        $this->app->make('Properos\Commerce\Controllers\Order\ApiOrderController');
        $this->app->make('Properos\Commerce\Controllers\OrderItem\ApiOrderItemController');
        $this->app->make('Properos\Commerce\Controllers\Plan\PlanController');
        $this->app->make('Properos\Commerce\Controllers\Plan\ApiPlanController');
        $this->app->make('Properos\Commerce\Controllers\Subscription\SubscriptionController');
        $this->app->make('Properos\Commerce\Controllers\Subscription\ApiSubscriptionController');
        $this->app->make('Properos\Commerce\Controllers\Wishlist\WishlistController');
        $this->app->make('Properos\Commerce\Controllers\Wishlist\ApiWishlistController');
        $this->app->make('Properos\Commerce\Controllers\Account\AccountController');
        $this->app->make('Properos\Commerce\Controllers\Account\ApiAccountController');
        $this->app->make('Properos\Commerce\Controllers\Location\ApiLocationController');
        $this->app->make('Properos\Commerce\Controllers\Collection\CollectionController');
        $this->app->make('Properos\Commerce\Controllers\Collection\ApiCollectionController');
    }
}
