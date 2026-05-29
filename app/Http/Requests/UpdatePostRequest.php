<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route('post')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:5|max:255',
            'body' => 'sometimes|required|string|min:100|max:1000',
            'category_ids' => 'sometimes|array',
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
