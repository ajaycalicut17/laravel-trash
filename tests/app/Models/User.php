<?php

namespace Ajaycalicut17\LaravelTrash\Tests\app\Models;

use Ajaycalicut17\LaravelTrash\Events\ModelTrashed;
use Ajaycalicut17\LaravelTrash\Tests\database\factories\UserFactory;
use Ajaycalicut17\LaravelTrash\Traits\Trashable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, Trashable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'trashed' => ModelTrashed::class,
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
