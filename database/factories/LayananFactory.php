<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Layanan> */
class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    public function definition(): array
    {
        return [
            'nama_layanan' => fake()->randomElement([
                'Cuci Reguler',
                'Cuci Express',
                'Cuci Setrika',
                'Setrika Saja',
            ]),

            'harga_per_kg' => fake()->randomElement([
                5000,
                7000,
                10000,
                12000,
            ]),
        ];
    }
}
