<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'min:3', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            // Password boleh dikosongkan jika tidak ingin diganti.
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'no_hp' => ['nullable', 'required_if:role,'.User::ROLE_PELANGGAN, 'string', 'max:20'],
            'alamat' => ['nullable', 'required_if:role,'.User::ROLE_PELANGGAN, 'string', 'max:500'],
        ];
    }

    /**
     * Admin tidak boleh menurunkan role akunnya sendiri agar tidak terkunci dari sistem.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function ($validator): void {
                /** @var User $user */
                $user = $this->route('user');

                if ($user->is($this->user()) && $this->input('role') !== $user->role) {
                    $validator->errors()->add('role', 'Kamu tidak dapat mengubah peran akun yang sedang digunakan.');
                }
            },
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
