<?php

namespace Ajaycalicut17\LaravelTrash\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Trash extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function trashable(): MorphTo
    {
        return $this->morphTo()
            ->withTrashed();
    }
}
