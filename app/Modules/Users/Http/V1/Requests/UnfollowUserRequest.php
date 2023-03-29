<?php

namespace App\Modules\Users\Http\V1\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UnfollowUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'unfollowing_user_uuid' => 'required|exists:users,uuid',
        ];
    }

    public function getUnfollowUser(): User
    {
        return User::findByUuid($this->input('unfollowing_user_uuid'));
    }
}
