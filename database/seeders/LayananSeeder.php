<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // Memastikan layanan Cuci Reguler tersedia.
        $reguler = Layanan::where(
            'nama_layanan',
            'Cuci Reguler'
        )->orderBy('id')->first();

        if ($reguler) {
            $reguler->update([
                'harga_per_kg' => 7000,
            ]);
        } else {
            Layanan::create([
                'nama_layanan' => 'Cuci Reguler',
                'harga_per_kg' => 7000,
            ]);
        }

        // Memastikan layanan Cuci Setrika tersedia.
        $setrika = Layanan::where(
            'nama_layanan',
            'Cuci Setrika'
        )->orderBy('id')->first();

        if ($setrika) {
            $setrika->update([
                'harga_per_kg' => 8000,
            ]);
        } else {
            Layanan::create([
                'nama_layanan' => 'Cuci Setrika',
                'harga_per_kg' => 8000,
            ]);
        }

        // Memastikan layanan Cuci Express tersedia.
        $express = Layanan::where(
            'nama_layanan',
            'Cuci Express'
        )->orderBy('id')->first();

        if ($express) {
            $express->update([
                'harga_per_kg' => 10000,
            ]);
        } else {
            Layanan::create([
                'nama_layanan' => 'Cuci Express',
                'harga_per_kg' => 10000,
            ]);
        }
    }
}
