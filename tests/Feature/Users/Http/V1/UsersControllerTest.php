<?php

namespace Tests\Feature\Users\Http\V1;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Illuminate\Support\Str;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    public function testIndexReturnsAListOfUsers()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $this->json('GET', 'v1/users')
            ->assertOk()
            ->assertJsonIsArray('data') // I contributed this method to Laravel Core ;)
            ->assertJsonFragment([
                'uuid' => $userA->uuid,
                'name' => $userA->name,
                'email' => $userA->email,
            ])
            ->assertJsonFragment([
                'uuid' => $userB->uuid,
                'name' => $userB->name,
                'email' => $userB->email,
            ]);
    }

    public function testIndexReturnsAListOfUsersSortByNameDesc()
    {
        [$userA, $userB] = User::factory()->count(2)
            ->sequence(
                [
                    'name' => 'Seth Phat',
                ],
                [
                    'name' => 'Alex',
                ]
            )
            ->create();

        $this->json('GET', 'v1/users', [
            'sort_by' => 'name',
            'sort_direction' => 'desc',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonPath('data', [
                [
                    'uuid' => $userA->uuid,
                    'name' => $userA->name,
                    'email' => $userA->email,
                ],
                [
                    'uuid' => $userB->uuid,
                    'name' => $userB->name,
                    'email' => $userB->email,
                ],
            ]);
    }

    public function testIndexReturnsAListOfUsersSortByCreatedAtDesc()
    {
        [$userA, $userB] = User::factory()->count(2)
            ->sequence(
                [
                    'name' => 'Seth Phat',
                    'created_at' => '2023-03-03',
                ],
                [
                    'name' => 'Alex',
                    'created_at' => '2023-03-04',
                ]
            )
            ->create();

        $this->json('GET', 'v1/users', [
            'sort_by' => 'created_at',
            'sort_direction' => 'desc',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonPath('data', [
                [
                    'uuid' => $userB->uuid,
                    'name' => $userB->name,
                    'email' => $userB->email,
                ],
                [
                    'uuid' => $userA->uuid,
                    'name' => $userA->name,
                    'email' => $userA->email,
                ],
            ]);
    }

    public function testIndexReturnsAListOfUsersFilterByName()
    {
        [$userA, $userB] = User::factory()->count(2)
            ->sequence(
                [
                    'name' => 'Seth Phat',
                    'email' => 'sethphat@gmail.com',
                    'created_at' => '2023-03-03',
                ],
                [
                    'name' => 'Alex',
                    'email' => 'alex@gmail.com',
                    'created_at' => '2023-03-04',
                ]
            )
            ->create();

        // search "Seth"
        $this->json('GET', 'v1/users', [
            'search' => 'Seth',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'uuid' => $userA->uuid,
                'name' => $userA->name,
                'email' => $userA->email,
            ])
            ->assertJsonMissing([
                'uuid' => $userB->uuid,
                'name' => $userB->name,
                'email' => $userB->email,
            ]);

        // Search "Phat"
        $this->json('GET', 'v1/users', [
            'search' => 'Phat',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'uuid' => $userA->uuid,
                'name' => $userA->name,
                'email' => $userA->email,
            ])
            ->assertJsonMissing([
                'uuid' => $userB->uuid,
                'name' => $userB->name,
                'email' => $userB->email,
            ]);

        // Search "Al"
        $this->json('GET', 'v1/users', [
            'search' => 'Al',
        ])
            ->assertOk()
            ->assertJsonIsArray('data')
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'uuid' => $userB->uuid,
                'name' => $userB->name,
                'email' => $userB->email,
            ])
            ->assertJsonMissing([
                'uuid' => $userA->uuid,
                'name' => $userA->name,
                'email' => $userA->email,
            ]);
    }

    public function testShowReturnsUserDetail()
    {
        $user = User::factory()->create();

        $this->json('GET', "v1/users/{$user->uuid}")
            ->assertOk()
            ->assertJsonIsObject('data')  // I contributed this method to Laravel Core ;)
            ->assertJsonFragment([
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function testShowReturnsNotFound()
    {
        $this->json('GET', 'v1/users/' . Str::uuid())
            ->assertNotFound();
    }

    public function testFollowFollowsAnUser()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $this->json('POST', "v1/users/{$userA->uuid}/follow", [
            'following_user_uuid' => $userB->uuid,
        ])
            ->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $this->assertDatabaseHas('user_followers', [
            'user_id' => $userA->id,
            'following_user_id' => $userB->id,
        ]);
    }

    public function testFollowFollowsAnFollowedUserReturnsError()
    {
        $userFollower = UserFollower::factory()->create();
        $userA = $userFollower->user;
        $userB = $userFollower->followingUser;

        $this->json('POST', "v1/users/{$userA->uuid}/follow", [
            'following_user_uuid' => $userB->uuid,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('following_user_uuid');
    }

    public function testUnfollowUnfollowsAnUser()
    {
        $userFollower = UserFollower::factory()->create();
        $userA = $userFollower->user;
        $userB = $userFollower->followingUser;

        $this->json('POST', "v1/users/{$userA->uuid}/unfollow", [
            'unfollowing_user_uuid' => $userB->uuid,
        ])
            ->assertOk()
            ->assertJsonFragment([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('user_followers', [
            'user_id' => $userA->id,
            'following_user_id' => $userB->id,
        ]);
    }

    public function testUnfollowReturnsErrorOnNotFollowedThatUser()
    {
        [$userA, $userB] = User::factory()->count(2)->create();

        $this->json('POST', "v1/users/{$userA->uuid}/unfollow", [
            'unfollowing_user_uuid' => $userB->uuid,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('unfollowing_user_uuid');
    }
}
