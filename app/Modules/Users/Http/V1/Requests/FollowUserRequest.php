<?php

namespace App\Modules\Users\Http\V1\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class FollowUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'following_user_uuid' => 'required|exists:users,uuid',
        ];
    }

    public function getFollowingUser(): User
    {
        return User::findByUuid($this->input('following_user_uuid'));
    }
}
