<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Status;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'emailVerifiedAt',
        'password',
        'photoUrl',
        'avatarTextColor',
        'gender',
        'about',
        'dateOfBirthday',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'rememberToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'emailVerifiedAt' => 'datetime',
        'password'          => 'hashed',
        'role' => Role::class,
    ];



    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['authProvider'];

    /**
     * Scope a query to only include user with status "active".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    /**
     * Scope a query to only include user with status "inactive".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', Status::INACTIVE);
    }

    /**
     * Relationship between User and AuthProvider
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authProvider()
    {
        return $this->hasOne(AuthProvider::class, 'userId', 'id');
    }

    /**
     * Relationship between User and ClashOfClansPlayer
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function player()
    {
        return $this->hasOne(ClashOfClansPlayer::class, 'userId', 'id');
    }

    /**
     * Relationship between User and BaseLayout
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baseLayouts()
    {
        return $this->hasMany(BaseLayout::class, 'userId', 'id');
    }

    /**
     * Check if user is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    /**
     * Check if user is general user
     * @return bool
     */
    public function isGeneralUser(): bool
    {
        return $this->role === Role::GENERAL_USER;
    }
}
