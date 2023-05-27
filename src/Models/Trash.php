<?php

namespace Ajaycalicut17\LaravelTrash\Models;

use Ajaycalicut17\LaravelTrash\Events\DeleteModel;
use Ajaycalicut17\LaravelTrash\Events\RestoreModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\LazyCollection;

class Trash extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => DeleteModel::class,
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

        RestoreModel::dispatch($this);

        return $this;
    }

    /**
     * delete trashed model and associated model.
     */
    public function deleteFromTrash(): bool|null
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
}
