# Laravel Trash 🗑️

[![GitHub Actions](https://img.shields.io/github/actions/workflow/status/ajaycalicut17/laravel-trash/run-tests.yml?branch=main&label=tests)](https://github.com/ajaycalicut17/laravel-trash/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajaycalicut17/laravel-trash)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
[![Total Downloads](https://img.shields.io/packagist/dt/ajaycalicut17/laravel-trash)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
[![License](https://img.shields.io/packagist/l/ajaycalicut17/laravel-trash)](https://packagist.org/packages/ajaycalicut17/laravel-trash)

This package is used to handle soft deletes. This is done using Laravel "soft delete" functionality.

## Installation ⚙️

- You can install the package via composer:

```bash
composer require ajaycalicut17/laravel-trash
```

- Run migrations to create tables for this package:

```php
php artisan migrate
```

- Check model is "soft delete" able, this package work by using laravel "soft delete" functionality:

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

- To enable this package for a model, add the Ajaycalicut17\LaravelTrash\Traits\Trashable trait to the Eloquent model:

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

- To start listening "trashed" model event, define a $dispatchesEvents property on your Eloquent model:

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

- To periodically delete the model from the trash, add model:prune Artisan command in your application's App\Console\Kernel class (Optional):

```diff
/**
 * Define the application's command schedule.
 */
protected function schedule(Schedule $schedule): void
{
+    $schedule->command('model:prune')->daily();
}
```

- To override the trash name (Optional):

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
+        return static::class.' '.$model->id;
+    }
}
```

- Publishing the config file (Optional):

```php
php artisan vendor:publish --provider="Ajaycalicut17\LaravelTrash\LaravelTrashServiceProvider" --tag="config"
```

## Usage 🔨

- To get all trash model data:

```php
Trash::all();
```

- To get all trash model and associated model data:

```php
Trash::with('trashable')->get();
```

- To restore associated model form trash:

```php
Trash::first()->restoreFromTrash();
```

- To delete trashed model and associated model:

```php
Trash::first()->deleteFromTrash();
```

- To delete all trashed model and associated model:

```php
Trash::emptyTrash();
```

## Testing 🧪

```bash
composer test
```

## Changelog 🚀

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing 🤝

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits 🔥

-   [Ajay A](https://github.com/ajaycalicut17)
-   [All Contributors](../../contributors)

## License 📃

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
