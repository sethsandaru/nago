<?php

namespace App\Modules\Users\Http\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => 'nullable|min:20|max:100',
            'page' => 'nullable|min:1',
            'sort_by' => [
                'nullable',
                Rule::in(['created_at', 'name', 'email']),
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
}
