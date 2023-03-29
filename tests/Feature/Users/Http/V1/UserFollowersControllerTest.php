<?php

namespace Tests\Feature\Users\Http\V1;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Tests\TestCase;

class UserFollowersControllerTest extends TestCase
{
    public function testListFollowingUsersReturnsAListOfFollowingUsers()
    {
        $user = User::factory()->create();
        // follows to 3 users
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
            ]);

        // 1 of his own follower
        $follower = UserFollower::factory()->create(['following_user_id' => $user->id]);

        $this->json('GET', "v1/users/{$user->uuid}/following")
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                'name' => $userFollowers[0]->followingUser->name,
            ])
            ->assertJsonFragment([
                'name' => $userFollowers[1]->followingUser->name,
            ])
            ->assertJsonFragment([
                'name' => $userFollowers[2]->followingUser->name,
            ])
            ->assertJsonMissing([
                'name' => $follower->user->name,
            ]);
    }

    public function testListFollowingUsersReturnsAListOfFollowingUsersFilterByName()
    {
        $user = User::factory()->create();
        // follows to 3 users
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->json('GET', "v1/users/{$user->uuid}/following", [
            'search' => $userFollowers[0]->followingUser->name,
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => $userFollowers[0]->followingUser->name,
            ])
            ->assertJsonMissing([
                'name' => $userFollowers[1]->followingUser->name,
            ])
            ->assertJsonMissing([
                'name' => $userFollowers[2]->followingUser->name,
            ]);
    }

    public function testListUserFollowersReturnsAListOfFollower()
    {
        $user = User::factory()->create();
        // has 3 followers
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->create([
                'following_user_id' => $user->id,
            ]);

        // follows to 1 user
        $following = UserFollower::factory()->create(['user_id' => $user->id]);

        $this->json('GET', "v1/users/{$user->uuid}/followers")
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                'name' => $userFollowers[0]->user->name,
            ])
            ->assertJsonFragment([
                'name' => $userFollowers[1]->user->name,
            ])
            ->assertJsonFragment([
                'name' => $userFollowers[2]->user->name,
            ])
            ->assertJsonMissing([
                'name' => $following->followingUser->name,
            ]);
    }

    public function testListUserFollowersReturnsAListOfFollowerFilterByName()
    {
        $user = User::factory()->create();
        // has 3 followers
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->create([
                'following_user_id' => $user->id,
            ]);

        $this->json('GET', "v1/users/{$user->uuid}/followers", [
            'search' => $userFollowers[1]->user->name,
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => $userFollowers[1]->user->name,
            ])
            ->assertJsonMissing([
                'name' => $userFollowers[0]->user->name,
            ])
            ->assertJsonMissing([
                'name' => $userFollowers[2]->user->name,
            ]);
    }
}
