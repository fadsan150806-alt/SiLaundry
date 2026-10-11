<?php

namespace App\Http\Requests;

/**
 * Pesan validasi berbahasa Indonesia yang dipakai bersama oleh form tambah & edit pengguna.
 */
class UserValidationMessages
{
    /**
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi untuk peran pelanggan.',
            'email' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan oleh pengguna lain.',
            'username.regex' => 'Nama pengguna hanya boleh berisi huruf, angka, titik, tanda hubung, dan garis bawah.',
            'min.string' => ':attribute minimal :min karakter.',
            'max.string' => ':attribute maksimal :max karakter.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.in' => 'Peran yang dipilih tidak valid.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'name' => 'Nama',
            'username' => 'Nama Pengguna',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'role' => 'Peran',
            'no_hp' => 'Nomor HP',
            'alamat' => 'Alamat',
        ];
    }
}
