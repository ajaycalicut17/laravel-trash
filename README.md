# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajaycalicut17/laravel-trash.svg?style=flat-square)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
[![Total Downloads](https://img.shields.io/packagist/dt/ajaycalicut17/laravel-trash.svg?style=flat-square)](https://packagist.org/packages/ajaycalicut17/laravel-trash)
![GitHub Actions](https://github.com/ajaycalicut17/laravel-trash/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require ajaycalicut17/laravel-trash
```

Run the migrations to create the tables for this package:

```php
php artisan migrate
```

To enable trash for a model, add the Ajaycalicut17\LaravelTrash\Traits\Trashable trait to the model:

```php
<?php

namespace App\Models;

use Ajaycalicut17\LaravelTrash\Traits\Trashable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Trashable;
}
```

To start listening to model events, define a $dispatchesEvents property on your Eloquent model:

```php
<?php

namespace App\Models;

use Ajaycalicut17\LaravelTrash\Events\ModelTrashed;
use Ajaycalicut17\LaravelTrash\Traits\Trashable;
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
        'trashed' => ModelTrashed::class,
    ];
}
```

To override the trash name:

```php
<?php

namespace App\Models;

use Ajaycalicut17\LaravelTrash\Events\ModelTrashed;
use Ajaycalicut17\LaravelTrash\Traits\Trashable;
use Illuminate\Database\Eloquent\Model;
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
        'trashed' => ModelTrashed::class,
    ];

    public static function trashName(Model $model): string
    {
        return static::class . ' ' . $model->id;
    }
}
```

Publishing the config file is optional:

```php
php artisan vendor:publish --provider="Ajaycalicut17\LaravelTrash\LaravelTrashServiceProvider" --tag="config"
```

## Usage

```php
// Usage description here
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
