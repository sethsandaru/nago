<?php

namespace App\Modules\Users;

use App\Modules\Users\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/users_routes.php');
    }

    public function register(): void
    {
        $this->app->singleton(UserService::class);
    }
}
