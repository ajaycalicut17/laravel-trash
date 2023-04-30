<?php

namespace Ajaycalicut17\LaravelTrash\Traits;

use Ajaycalicut17\LaravelTrash\Models\Trash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Trashable
{
    public function trash(): MorphOne
    {
        return $this->morphOne(Trash::class, 'trashable');
    }

    public static function trashName(Model $model): string
    {
        return static::class . ' ' . $model->id;
    }
}
