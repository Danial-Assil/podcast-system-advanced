<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically hash password when setting.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Polymorphic relation for media.
     */
    public function media()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    /**
     * Get all comments by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
