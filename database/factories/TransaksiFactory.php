<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

/** @extends Factory<Transaksi> */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        // Mengambil layanan yang sudah tersedia di database.
        $layanan = Layanan::query()->inRandomOrder()->first();

        if (! $layanan) {
            throw new RuntimeException(
                'Data layanan belum tersedia. Buat data layanan terlebih dahulu.'
            );
        }

        // Mengambil pelanggan yang sudah tersedia.
        $pelanggan = Pelanggan::query()->inRandomOrder()->first();

        if (! $pelanggan) {
            throw new RuntimeException(
                'Data pelanggan belum tersedia. Buat data pelanggan terlebih dahulu.'
            );
        }

        // Mengambil user yang memiliki peran kasir.
        $kasir = User::query()
            ->where('role', 'kasir')
            ->inRandomOrder()
            ->first();

        if (! $kasir) {
            throw new RuntimeException(
                'Data kasir belum tersedia. Buat user dengan role kasir terlebih dahulu.'
            );
        }

        // Menentukan berat laundry antara 1 sampai 10 kg.
        $berat = fake()->randomFloat(2, 1, 10);

        return [
            'pelanggan_id' => $pelanggan->id,

            'layanan_id' => $layanan->id,

            'kasir_id' => $kasir->id,

            'berat_kg' => $berat,

            'total_harga' => round(
                $berat * (float) $layanan->harga_per_kg,
                2
            ),

            'status' => 'diterima',

            'tanggal_masuk' => now()->toDateString(),

            'tanggal_selesai' => null,
        ];
    }
}
