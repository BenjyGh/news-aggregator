<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'news_sources' => ['array'],
            'news_sources.*' => ['integer', 'exists:news_sources,id'],

            'categories' => ['array'],
            'categories.*' => ['integer', 'exists:categories,id'],

            'authors' => ['array'],
            'authors.*' => ['integer', 'exists:authors,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'news_sources.*.exists' => 'One or more selected news sources are invalid.',
            'categories.*.exists' => 'One or more selected categories are invalid.',
            'authors.*.exists' => 'One or more selected authors are invalid.',
        ];
    }
}
