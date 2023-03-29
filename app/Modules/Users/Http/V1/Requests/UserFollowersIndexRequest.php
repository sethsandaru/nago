<?php

namespace App\Modules\Users\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFollowersIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => 'nullable|min:20|max:100',
            'page' => 'nullable|min:1',
            'sort_by' => [
                'nullable',
                Rule::in(['followed_at', 'name']),
            ],
            'sort_direction' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
            'search' => 'nullable|string',
        ];
    }

    public function getPagingLimit(): int
    {
        return $this->integer('limit') ?: 20;
    }

    public function getSortByColumn(): string
    {
        return $this->validated('sort_by') ?: 'created_at';
    }

    public function getSortDirection(): string
    {
        return $this->validated('sort_direction') ?: 'ASC';
    }
}
