<?php

namespace Ajaycalicut17\LaravelTrash\Models;

use Ajaycalicut17\LaravelTrash\Events\DeleteFromTrash;
use Ajaycalicut17\LaravelTrash\Events\RestoreFromTrash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\LazyCollection;

class Trash extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'name',
    ];

    /**
     * The event map for the model.
     */
    protected $dispatchesEvents = [
        'deleted' => DeleteFromTrash::class,
    ];

    public function trashable(): MorphTo
    {
        return $this->morphTo()
            ->withTrashed();
    }

    /**
     * restore associated model form trash.
     */
    public function restoreFromTrash(): Trash
    {
        $this->deleteQuietly();

        RestoreFromTrash::dispatch($this);

        return $this;
    }

    /**
     * delete trashed model and associated model.
     */
    public function deleteFromTrash(): ?bool
    {
        return $this->delete();
    }

    /**
     * delete all trashed model and associated model.
     */
    public static function emptyTrash(): LazyCollection
    {
        return static::cursor()->each(function ($each) {
            $each->delete();
        });
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', config('trash.pruning_period'))
            ->when(! config('trash.pruning_status'), function ($when) {
                $when->whereNull('id');
            });
    }
}
