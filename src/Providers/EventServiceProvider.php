<?php

namespace Ajaycalicut17\LaravelTrash\Providers;

use Ajaycalicut17\LaravelTrash\Events\DeleteFromTrash;
use Ajaycalicut17\LaravelTrash\Events\MoveToTrash;
use Ajaycalicut17\LaravelTrash\Events\RestoreFromTrash;
use Ajaycalicut17\LaravelTrash\Listeners\DeletedFromTrash;
use Ajaycalicut17\LaravelTrash\Listeners\RestoredFromTrash;
use Ajaycalicut17\LaravelTrash\Listeners\MovedToTrash;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        MoveToTrash::class => [
            MovedToTrash::class
        ],
        RestoreFromTrash::class => [
            RestoredFromTrash::class
        ],
        DeleteFromTrash::class => [
            DeletedFromTrash::class
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
