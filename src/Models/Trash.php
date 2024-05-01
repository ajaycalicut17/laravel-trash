<?php

namespace Ajaycalicut17\LaravelTrash\Models;

use Ajaycalicut17\LaravelTrash\Events\DeleteFromTrash;
use Ajaycalicut17\LaravelTrash\Events\RestoreFromTrash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Trash extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'name',
    ];

    /**
     * The event map for the model.
     *
     * @var array
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
     * Restore the associated model from the trash.
     */
    public function restoreFromTrash(): self
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
     * Delete all trashed models and their associated models.
     */
    public static function emptyTrash(): void
    {
        static::cursor()->each(fn (Trash $trash) => $trash->delete());
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', config('trash.pruning.period'))
            ->when(! config('trash.pruning.status'), function ($when) {
                $when->whereNull('id');
            });
    }
}
