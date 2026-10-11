<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'no_hp' => ['nullable', 'required_if:role,'.User::ROLE_PELANGGAN, 'string', 'max:20'],
            'alamat' => ['nullable', 'required_if:role,'.User::ROLE_PELANGGAN, 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return UserValidationMessages::messages();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return UserValidationMessages::attributes();
    }
}
