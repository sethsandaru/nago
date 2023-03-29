<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFollowerFactory extends Factory
{
    protected $model = UserFollower::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->create(),
            'following_user_id' => fn () => User::factory(),
        ];
    }
}
