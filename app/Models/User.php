<?php

namespace App\Models;

use App\Modules\Users\Models\UserFollower;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
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

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * A user can follow to multiple other users
     */
    public function followingUsers(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'user_id');
    }

    /**
     * A user can have many followers
     */
    public function userFollowers(): HasMany
    {
        return $this->hasMany(UserFollower::class, 'following_user_id');
    }

    /**
     * A user can follow to multiple other users - but directly access to the User
     */
    public function followingUsersUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_followers',
            'user_id',
            'following_user_id'
        );
    }

    /**
     * A user can have many followers - directly access to User
     */
    public function userFollowersUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_followers',
            'following_user_id',
            'user_id',
        );
    }

    public static function findByUuid(string $uuid): ?User
    {
        return self::where('uuid', $uuid)->first();
    }

    public function isAlreadyFollowed(User $userToFollow): bool
    {
        return $this->followingUsers()
            ->where('following_user_id', $userToFollow->id)
            ->exists();
    }
}
