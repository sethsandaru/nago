<?php

namespace App\Modules\Users\Models;

use App\Models\User;
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
    protected $table = 'user_followers';

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
}
