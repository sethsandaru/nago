<?php

namespace Tests\Unit\Users\Services;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use App\Modules\Users\Services\UserService;
use RuntimeException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(UserService::class);
    }

    public function testCallFollowUserMethodWithoutSetRequiredPropertyThrowsRuntimeException()
    {
        $this->expectException(RuntimeException::class);

        $this->service->followUser(new User());
    }

    public function testCallUnfollowUserMethodWithoutSetRequiredPropertyThrowsRuntimeException()
    {
        $this->expectException(RuntimeException::class);

        $this->service->unfollowUser(new User());
    }

    public function testFollowUserFollowsAnotherUser()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $userFollower = $this->service->setUser($userA)
            ->followUser($userB);

        $this->assertNotNull($userFollower);
        $this->assertDatabaseHas($userFollower->getTable(), [
            'user_id' => $userA->id,
            'following_user_id' => $userB->id,
        ]);
    }

    public function testUnfollowUserUnfollowsAnotherUser()
    {
        $userFollower = UserFollower::factory()->create();

        $unfollowResult = $this->service
            ->setUser($userFollower->user)
            ->unfollowUser($userFollower->followingUser);

        $this->assertTrue($unfollowResult);

        $this->assertDatabaseMissing($userFollower->getTable(), [
            'user_id' => $userFollower->user_id,
            'following_user_id' => $userFollower->following_user_id,
        ]);
    }
}
