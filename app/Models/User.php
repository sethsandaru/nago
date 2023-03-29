<?php

namespace App\Models;

use App\Modules\Users\Models\UserFollower;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function userFollowers(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'user_id');
    }

    public function followingUsers(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'following_user_id');
    }

    public static function findByUuid(string $uuid): ?User
    {
        return self::where('uuid', $uuid)->first();
    }
}
