# PHP Loyalty
Point Transaction system for laravel 5

## Installation

First, pull in the package through Composer.

```js
composer require nu1ww/loyalty
```

And then include the service provider within `app/config/app.php`.

```php
'providers' => [
    Loyalty\LoyaltyServiceProvider::class
];
```

At last you need to publish and run the migration.
```
php artisan vendor:publish --provider="Loyalty\PointableServiceProvider" && php artisan migrate
```

-----

### Setup a Model
```php
<?php

namespace App;

use Loyalty\Contracts\Loyalty;
use Loyalty\Traits\LoyaltyTrait as LoyaltyTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Loyalty
{
    use LoyaltyTrait;
}
```

### Earn Points
```php

    $user = \App\User::first();
    $amount = 10;  
    $message = "";
 
    $data = [];
    $transaction = $user->earnPoints($amount, $message, $data);
 
```

### Burn Points 
You can give negative and positive values
 
```php

    $user = \App\User::first();
    $amount = 10;  
    $message = "";
 
    $data = [];
    $transaction = $user->burnPoints($amount, $message, $data);
 
```

### Get Available Points by User
```php

$point;
 
```

### Count Transactions
```php

$point;
 
```

### Paginated data list
```php

$point;
 
```
### Tiger notification 
```php

$point;
 
```