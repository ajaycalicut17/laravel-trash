<?php

namespace Ajaycalicut17\LaravelTrash\Providers;

use Ajaycalicut17\LaravelTrash\Events\DeleteModel;
use Ajaycalicut17\LaravelTrash\Events\ModelTrashed;
use Ajaycalicut17\LaravelTrash\Events\RestoreModel;
use Ajaycalicut17\LaravelTrash\Listeners\Delete;
use Ajaycalicut17\LaravelTrash\Listeners\Restore;
use Ajaycalicut17\LaravelTrash\Listeners\Trashed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ModelTrashed::class => [
            Trashed::class
        ],
        RestoreModel::class => [
            Restore::class
        ],
        DeleteModel::class => [
            Delete::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
