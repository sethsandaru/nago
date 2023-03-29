<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Users\Models\UserFollower;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sethPhatUser = User::factory()->create([
            'name' => 'Seth Phat',
            'email' => 'me@sethphat.com',
        ]);

        $jarekUser = User::factory()->create([
            'name' => 'Jarek Tkaczyk',
            'email' => 'me@jarek.com',
        ]);

        $taylorUser = User::factory()->create([
            'name' => 'Taylor Otwell',
            'email' => 'me@laravel.com',
        ]);

        User::factory()->count(50)->create();

        // Seth Phat follows Jarek & Taylor
        UserFollower::factory()->create([
            'user_id' => $sethPhatUser->id,
            'following_user_id' => $jarekUser->id,
        ]);

        UserFollower::factory()->create([
            'user_id' => $sethPhatUser->id,
            'following_user_id' => $taylorUser->id,
        ]);

        // Jarek follows Seth Phat
        UserFollower::factory()->create([
            'user_id' => $jarekUser->id,
            'following_user_id' => $sethPhatUser->id,
        ]);

        // Taylor follows Jarek
        UserFollower::factory()->create([
            'user_id' => $taylorUser->id,
            'following_user_id' => $jarekUser->id,
        ]);
    }
}
