<?php

namespace App\Modules\Users\Models;

use App\Models\User;
use Database\Factories\UserFollowerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $user_id
 * @property int $following_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $followingUser
 * @property User $user
 */
class UserFollower extends Model
{
    use HasFactory;

    protected $table = 'user_followers';

    protected $fillable = [
        'user_id',
        'following_user_id',
    ];

    protected $casts = [
        'user_id' => 'int',
        'following_user_id' => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followingUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_user_id');
    }

    protected static function newFactory(): UserFollowerFactory
    {
        return new UserFollowerFactory();
    }
}
