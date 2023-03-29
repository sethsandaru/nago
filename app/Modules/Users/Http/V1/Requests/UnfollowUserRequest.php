<?php

namespace App\Modules\Users\Http\V1\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UnfollowUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'unfollowing_user_uuid' => 'required|exists:users,uuid',
        ];
    }

    protected function passedValidation(): void
    {
        /** @var User $user */
        $user = $this->route('user');
        $followingUser = $this->getUnfollowUser();

        if (!$user->isAlreadyFollowed($followingUser)) {
            throw ValidationException::withMessages([
                'unfollowing_user_uuid' => __('You are not following this user.'),
            ]);
        }
    }

    public function getUnfollowUser(): User
    {
        return User::findByUuid($this->input('unfollowing_user_uuid'));
    }
}
