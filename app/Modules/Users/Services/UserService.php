<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use RuntimeException;

class UserService
{
    protected User $user;

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    private function validateRequiredProperties(): void
    {
        if (!isset($this->user)) {
            throw new RuntimeException('$user must be set in order to use');
        }
    }

    public function followUser(User $userToFollow): UserFollower
    {
        $this->validateRequiredProperties();

        return UserFollower::create([
            'user_id' => $this->user->id,
            'following_user_id' => $userToFollow->id,
        ]);
    }

    public function unfollowUser(User $userToUnfollow): bool
    {
        $this->validateRequiredProperties();

        return UserFollower::where([
            'user_id' => $this->user->id,
            'following_user_id' => $userToUnfollow->id,
        ])->delete();
    }
}
