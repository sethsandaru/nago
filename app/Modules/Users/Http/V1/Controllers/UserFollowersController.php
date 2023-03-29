<?php

namespace App\Modules\Users\Http\V1\Controllers;

use App\Models\User;
use App\Modules\Users\Http\V1\Requests\UserFollowersIndexRequest;
use App\Modules\Users\Http\V1\Resources\FollowingUserResource;
use App\Modules\Users\Http\V1\Resources\UserFollowerResource;
use Illuminate\Http\JsonResponse;

class UserFollowersController
{
    public function listFollowingUsers(
        UserFollowersIndexRequest $request,
        User $user
    ): JsonResponse {
        $followingUsers = $user->followingUsers()
            ->orderBy($request->getSortByColumn(), $request->getSortDirection())
            ->when(
                $request->filled('search'),
                fn ($query) => $query->whereHas('followingUser', function ($query) use ($request) {
                    $query->where('name', 'LIKE', "%{$request->input('search')}%");
                })
            )
            ->with('followingUser')
            ->paginate($request->getPagingLimit());

        return FollowingUserResource::collection($followingUsers)
            ->response();
    }

    public function listUserFollowers(
        UserFollowersIndexRequest $request,
        User $user
    ): JsonResponse {
        $userFollowers = $user->userFollowers()
            ->orderBy($request->getSortByColumn(), $request->getSortDirection())
            ->when(
                $request->filled('search'),
                fn ($query) => $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', "%{$request->input('search')}%");
                })
            )
            ->with('user')
            ->paginate($request->getPagingLimit());

        return UserFollowerResource::collection($userFollowers)
            ->response();
    }
}
