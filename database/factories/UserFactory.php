<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(),
            'role' => fake()->randomElement([
                'admin',
                'kasir',
                'pelanggan',
            ]),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function kasir(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_KASIR,
        ]);
    }

    public function pelanggan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_PELANGGAN,
        ]);
    }

    /**
     * Membuat User yang belum memverifikasi email.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
