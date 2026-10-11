<?php

namespace Database\Factories;

use App\Models\Notifikasi;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

/** @extends Factory<Notifikasi> */
class NotifikasiFactory extends Factory
{
    protected $model = Notifikasi::class;

    public function definition(): array
    {
        // Mengambil transaksi yang sudah tersedia.
        $transaksi = Transaksi::query()->inRandomOrder()->first();

        if (! $transaksi) {
            throw new RuntimeException(
                'Data transaksi belum tersedia. Buat data transaksi terlebih dahulu.'
            );
        }

        return [
            'transaksi_id' => $transaksi->id,

            'pesan' => fake()->randomElement([
                'Transaksi laundry telah diterima.',
                'Transaksi laundry sedang diproses.',
                'Transaksi laundry telah selesai.',
            ]),

            'tanggal_kirim' => now(),
        ];
    }
}
