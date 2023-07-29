# Laravel Trash

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajaycalicut17/laravel-trash.svg?style=flat-square)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
[![Total Downloads](https://img.shields.io/packagist/dt/ajaycalicut17/laravel-trash.svg?style=flat-square)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
![GitHub Actions](https://github.com/ajaycalicut17/laravel-trash/actions/workflows/main.yml/badge.svg)

This package is used to handle soft deletes. This is done using Laravel "soft delete" functionality.

## Installation

You can install the package via composer:

```bash
composer require ajaycalicut17/laravel-trash
```

## Usage

Run migrations to create tables for this package:

```php
php artisan migrate
```

Check model is "soft delete" able, this package work by using laravel "soft delete" functionality:

```diff
<?php

namespace App\Models;

+ use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
+    use SoftDeletes;
}
```

To enable this package for a model, add the Ajaycalicut17\LaravelTrash\Traits\Trashable trait to the Eloquent model:

```diff
<?php

namespace App\Models;

+ use Ajaycalicut17\LaravelTrash\Traits\Trashable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
+    use SoftDeletes, Trashable;
}
```

To start listening "trashed" model event, define a $dispatchesEvents property on your Eloquent model:

```diff
<?php

namespace App\Models;

+ use Ajaycalicut17\LaravelTrash\Events\MoveToTrash;
use Ajaycalicut17\LaravelTrash\Traits\Trashable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Trashable;
+
+    /**
+     * The event map for the model.
+     *
+     * @var array
+     */
+    protected $dispatchesEvents = [
+        'trashed' => MoveToTrash::class,
+    ];
}
```

To override the trash name (Optional):

```diff
<?php

namespace App\Models;

use Ajaycalicut17\LaravelTrash\Events\MoveToTrash;
use Ajaycalicut17\LaravelTrash\Traits\Trashable;
+ use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Trashable;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'trashed' => MoveToTrash::class,
    ];
+
+    public static function trashName(Model $model): string
+    {
+        return static::class . ' ' . $model->id;
+    }
}
```

Publishing the config file (Optional):

```php
php artisan vendor:publish --provider="Ajaycalicut17\LaravelTrash\LaravelTrashServiceProvider" --tag="config"
```

To get all trash model data:

```php
Trash::all();
```

To get all trash model and associated model data:

```php
Trash::with('trashable')->get();
```

To restore associated model form trash:

```php
Trash::first()->restoreFromTrash();
```

To delete trashed model and associated model:

```php
Trash::first()->deleteFromTrash();
```

To delete all trashed model and associated model:

```php
Trash::emptyTrash();
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Ajay A](https://github.com/ajaycalicut17)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
