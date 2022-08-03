## Properos Commerce

Commerce package.

**Required properos/properos-base package**
**Required properos/properos-users package**

**Required properos/laravel5-amazon-mws package**
```php
composer require properos/laravel5-amazon-mws
Configuration => https://github.com/properos/amazon-mws-laravel
```

**Required intervention/image package**
```php
composer require intervention/image
Configuration => http://image.intervention.io/getting_started/introduction
```

**Required authorizenet/authorizenet package**
```php
composer require authorizenet/authorizenet
Configuration => https://packagist.org/packages/authorizenet/authorizenet
```

**Required doctrine/dbal package**
```php
composer require doctrine/dbal
Configuration => https://packagist.org/packages/doctrine/dbal
```

**Required moment.js**
```php
npm install moment 
```

**Add on resources/assets/bootstrap.js if not exist**
```js
    import Helpers from './misc/helpers'

    window.moment = require('moment')
    window.Vue - require('vue');
    window.Helpers = Helpers;
```

**Create env.js**

**Add on config/app.php**
```php
    'providers' => [
        '...',
        Properos\Commerce\CommerceServiceProvider::class,
        '...'
    ]
```

**Register provider on composer.json**
```json
    "autoload": {
    "...": {},
        "psr-4": {
            "App\\": "app/",
            "Properos\\Commerce\\": "packages/properos/properos-commerce/src"
        }
    },
```

**Run**
```php
    composer dump
    php artisan vendor:publish --force
Select -> Properos\Commerce\CommerceServiceProvider  
    php artisan storage:link
```

**Add on webpack.mix.js**
.js('resources/assets/js/be/modules/commerce/js/commerce.js', 'public/be/js/modules/commerce.js')
.js('resources/assets/js/fe/modules/commerce/js/commerce.js', 'public/themes/default/modules/js/commerce.js')
js('resources/assets/js/app.js', 'public/themes/default/js')

**properos_commerce file**
Set the middleware for the routes.

**How to use a Model**
\Properos\Commerce\Models\Model-Name


**Modify config/database.php**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => 'Innodb',
],
```

**Run migrations**
```php
php artisan migrate
    create  
```

**Add on Properos\Users\Models\User**
```php
use UserTrait;
```

**Emails messages add on .env**
```php
ORDER_CREATED_MSG=""
ORDER_FULFILLED_MSG=""

SUBSCRIBER_WELCOME=""
```

**Card processor add on .env**
```php
CARD_PROCESSOR = authorize or stripe

AUTHORIZE_ENV=
AUTHORIZE_API_ID=
AUTHORIZE_PRIVATE_KEY=
AUTHORIZE_PUBLIC_KEY=

STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
STRIPE_STATEMENT_DESCRIPTOR=
STRIPE_API_URL=
```

*Paypal add on .env**
```php
PAYPAL=true
PAYPAL_ENV=
PAYPAL_ID=
PAYPAL_TOKEN=
```

**UPS add on .env**
```php
USPS_TRACK_URL_LABEL = "https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1="
UPS_TRACK_URL = "https://wwwapps.ups.com/tracking/tracking.cgi?tracknum="
FEDEX_TRACK_URL = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber="
DHL_US_TRACK_URL = "http://www.dhl.com/en/express/tracking.html?brand=DHL&AWB="
DHL_GLOBAL_TRACK_URL = "http://webtrack.dhlglobalmail.com/?trackingnumber="
USPS_TRACK_URL_CONFIRMATION = "http://www.stamps.com/shipstatus/submit/?confirmation="

UPS_API_INTEGRATION=
UPS_SHIPPING_API_ENV=
UPS_API_USER=
UPS_API_PASSWORD=
UPS_API_ACCESS_TOKEN=
UPS_API_ACCOUNT_NUMBER=
UPS_API_USER_ADDRESS=
UPS_API_USER_CITY=
UPS_API_USER_ZIP=
UPS_API_USER_STATE=
UPS_API_USER_COUNTRY=
UPS_SERVICES_ALLOWED=
```

**Amazon add on .env**
```php
ACTIVE_AMAZON=true
AMAZON_SELLER_ID=
AMAZON_MARKETPLACE_ID=
AMAZON_KEY_ID=
AMAZON_SECRET_KEY=
AMAZON_AWS_URL=
AMAZON_AUTH_TOKEN=
AMAZON_DISABLE_SSL=true
```

**Add seeder on database/seeds/DatabaseSeeder.php**
```php
    $this->call(PaymentMethodsTableSeeder::class);
    $this->call(ShippingMethodsTableSeeder::class);
```
**Run**
```php
composer dump-autoload
php artisan db:seed
npm run watch
```


