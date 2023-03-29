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

    public function testListFollowingUsersReturnsAListOfFollowingUsersSortByFollowedAtDesc()
    {
        $user = User::factory()->create();
        // follows to 3 users
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->sequence(
                ['created_at' => '2023-03-03 00:00:00'],
                ['created_at' => '2023-03-04 00:00:00'],
                ['created_at' => '2023-03-05 00:00:00'],
            )
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->json('GET', "v1/users/{$user->uuid}/following", [
            'sort_by' => 'followed_at',
            'sort_direction' => 'desc',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(3, 'data');

        $this->assertSame('2023-03-05 00:00:00', $response->json('data.0.followed_at'));
        $this->assertSame('2023-03-04 00:00:00', $response->json('data.1.followed_at'));
        $this->assertSame('2023-03-03 00:00:00', $response->json('data.2.followed_at'));
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

    public function testListUserFollowersReturnsAListOfFollowerSortByFollowedAtAsc()
    {
        $user = User::factory()->create();
        // has 3 followers
        $userFollowers = UserFollower::factory()
            ->count(3)
            ->sequence(
                ['created_at' => '2023-03-05 11:11:11'],
                ['created_at' => '2023-03-04 11:11:11'],
                ['created_at' => '2023-03-03 11:11:11'],
            )
            ->create([
                'following_user_id' => $user->id,
            ]);

        // follows to 1 user
        $following = UserFollower::factory()->create(['user_id' => $user->id]);

        $response = $this->json('GET', "v1/users/{$user->uuid}/followers", [
            'sort_by' => 'followed_at',
            'sort_direction' => 'asc',
        ])
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

        $this->assertSame(
            '2023-03-03 11:11:11',
            $response->json('data.0.followed_at')
        );
        $this->assertSame(
            '2023-03-04 11:11:11',
            $response->json('data.1.followed_at')
        );
        $this->assertSame(
            '2023-03-05 11:11:11',
            $response->json('data.2.followed_at')
        );
    }
}
