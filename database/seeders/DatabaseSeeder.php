<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Seth Phat',
            'email' => 'me@sethphat.com',
        ]);

        User::factory()->create([
            'name' => 'Jarek Tkaczyk',
            'email' => 'me@jarek.com',
        ]);

        User::factory()->create([
            'name' => 'Taylor Otwell',
            'email' => 'me@laravel.com',
        ]);

        User::factory()->count(50)->create();
    }
}
