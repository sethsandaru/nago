<?php

namespace App\Modules\Users\Http\V1\Resources;

use App\Modules\Users\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserFollower
 */
class UserFollowerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->name,
            'followed_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
