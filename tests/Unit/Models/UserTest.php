<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testUserHasManyFollowers()
    {
        $user = User::factory()->create();
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->create([
                'following_user_id' => $user->id,
            ]);

        $this->assertSame(3, $user->userFollowers()->count());
        $this->assertSame(3, $user->userFollowersUsers()->count());
    }

    public function testUserHasManyFollowingUsers()
    {
        $user = User::factory()->create();
        $userFollowers = UserFollower::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->assertSame(5, $user->followingUsers()->count());
        $this->assertSame(5, $user->followingUsersUsers()->count());
    }

    public function testUserIsAlreadyFollowedToAnotherUserReturnsTrue()
    {
        $userFollower = UserFollower::factory()->create();
        $user = $userFollower->user;
        $followedToUser = $userFollower->followingUser;

        $this->assertTrue($user->isAlreadyFollowed($followedToUser));
    }

    public function testUserIsAlreadyFollowedToAnotherUserReturnsFalse()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $this->assertFalse($userA->isAlreadyFollowed($userB));
    }
}
