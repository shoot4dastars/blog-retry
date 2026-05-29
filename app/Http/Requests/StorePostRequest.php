<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:100|max:1000',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'status' => 'sometimes|in:draft,published'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A post title is required.',
            'title.min' => 'The title must be at least 5 characters long.',
            'body.required' => 'Post content is required.',
            'body.min' => 'Your post content must be at least 100 characters long.',
        ];
    }
}
