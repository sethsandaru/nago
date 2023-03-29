<?php

namespace Tests\Unit\Users\Models;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Tests\TestCase;

class UserFollowerTest extends TestCase
{
    public function testUserFollowerBelongsToUser()
    {
        /** @var UserFollower $userFollower */
        $user = User::factory()->create();
        $userFollower = UserFollower::factory()->create([
            'user_id' => $user,
        ]);

        $this->assertTrue(
            $user->is(
                $userFollower->user()->first()
            )
        );
    }

    public function testUserFollowerBelongsToFollowingUser()
    {
        /** @var UserFollower $userFollower */
        $user = User::factory()->create();
        $userFollower = UserFollower::factory()->create([
            'following_user_id' => $user,
        ]);

        $this->assertTrue(
            $user->is(
                $userFollower->followingUser()->first()
            )
        );
    }
}
