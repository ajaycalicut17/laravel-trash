<?php

namespace Ajaycalicut17\LaravelTrash;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ajaycalicut17\LaravelTrash\Skeleton\SkeletonClass
 */
class LaravelTrashFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-trash';
    }
}
