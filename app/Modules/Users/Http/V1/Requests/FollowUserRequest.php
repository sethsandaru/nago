<?php

namespace App\Modules\Users\Http\V1\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class FollowUserRequest extends FormRequest
{
    private ?User $user;

    public function rules(): array
    {
        return [
            'following_user_uuid' => 'required|exists:users,uuid',
        ];
    }

    protected function passedValidation(): void
    {
        /** @var User $user */
        $user = $this->route('user');
        $followingUser = $this->getFollowingUser();

        if ($user->isAlreadyFollowed($followingUser)) {
            throw ValidationException::withMessages([
                'following_user_uuid' => __('You already followed this user'),
            ]);
        }
    }

    public function getFollowingUser(): User
    {
        return $this->user
            ??= User::findByUuid($this->input('following_user_uuid'));
    }
}
