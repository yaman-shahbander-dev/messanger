<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'user_id' => ['required', 'string', 'max:50', Rule::unique('users', 'user_name')->ignore(auth()->user()->id)],
            'email' => ['required', 'email', 'max:100'],
            'avatar' => ['nullable', 'image', 'max:500'],
            'current_password' => ['nullable', 'current_password', 'string'],
            'password' => ['required_with:current_password', 'string', 'min:8', 'confirmed']
        ];
    }
}
