<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam news_sources int[] List of news source Ids the user prefers. No-example
 * @bodyParam categories int[] List of category Ids the user prefers. No-example
 * @bodyParam authors int[] List of author Ids the user prefers. No-example
*/
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
            'news_sources' => ['present', 'array'],
            'news_sources.*' => ['integer', 'exists:news_sources,id'],

            'categories' => ['present', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],

            'authors' => ['present', 'array'],
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
