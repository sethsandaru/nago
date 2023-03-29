<?php

use App\Modules\Users\Http\V1\Controllers\UserFollowersController;
use App\Modules\Users\Http\V1\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware([
        'api',
        // 'auth',
    ])
    ->group(function () {
        Route::resource('users', UsersController::class)
            ->only(['index', 'show']);

        Route::post('users/{user}/follow', [UsersController::class, 'follow']);
        Route::post('users/{user}/unfollow', [UsersController::class, 'unfollow']);

        Route::get('users/{user}/followers', [UserFollowersController::class, 'index']);
    });
