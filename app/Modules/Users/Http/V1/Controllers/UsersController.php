<?php

namespace App\Modules\Users\Http\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Modules\Users\Http\V1\Requests\FollowUserRequest;
use App\Modules\Users\Http\V1\Requests\UnfollowUserRequest;
use App\Modules\Users\Http\V1\Requests\UsersIndexRequest;
use App\Modules\Users\Services\UserService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function index(UsersIndexRequest $request): JsonResponse
    {
        $users = User::query()
            ->orderBy($request->validated('sort_by'), $request->validated('sort_direction'))
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where('name', 'LIKE', "%{$request->input('search')}%")
                    ->orWhere('email', 'LIKE', "%{$request->input('search')}%")
            )
            ->paginate($request->getPagingLimit());

        return UserResource::collection($users)->response();
    }

    public function show(User $user): JsonResponse
    {
        return (new UserResource($user))->response();
    }

    public function follow(
        FollowUserRequest $request,
        User $user,
        UserService $service
    ): JsonResponse {
        $followResult = $service->setUser($user)
            ->followUser($request->getFollowingUser());

        return new JsonResponse([
            'success' => (bool) $followResult,
        ]);
    }

    public function unfollow(
        UnfollowUserRequest $request,
        User $user,
        UserService $service
    ): JsonResponse {
        $unfollowResult = $service->setUser($user)
            ->unfollowUser($request->getUnfollowUser());

        return new JsonResponse([
            'success' => $unfollowResult,
        ]);
    }
}
