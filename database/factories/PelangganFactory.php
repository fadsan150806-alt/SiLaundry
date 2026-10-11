<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pelanggan> */
class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'role' => 'pelanggan',
            ]),

            'nama' => fake()->name(),

            'no_hp' => fake()->numerify('08##########'),

            'alamat' => fake()->address(),
        ];
    }
}
