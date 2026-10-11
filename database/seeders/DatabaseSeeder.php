<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat akun administrator jika belum tersedia
        User::firstOrCreate(
            ['email' => 'admin@silaundry.test'],
            [
                'name' => 'Administrator SiLaundry',
                'username' => 'admin',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Membuat akun kasir jika belum tersedia
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@silaundry.test'],
            [
                'name' => 'Kasir SiLaundry',
                'username' => 'kasir',
                'role' => User::ROLE_KASIR,
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'siti.kasir@silaundry.test'],
            [
                'name' => 'Siti Rahmawati',
                'username' => 'siti',
                'role' => User::ROLE_KASIR,
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Membuat akun pelanggan beserta data pelanggannya
        $pelanggan = $this->createPelanggan(
            'pelanggan1@silaundry.test',
            'Budi Santoso',
            'budi',
            '081234567890',
            'Jl. Melati No. 10'
        );

        $pelangganDua = $this->createPelanggan(
            'pelanggan2@silaundry.test',
            'Dewi Lestari',
            'dewi',
            '082198765432',
            'Jl. Kenanga No. 5'
        );

        // 4. Memastikan data layanan tersedia
        $this->call([
            LayananSeeder::class,
        ]);

        // Mengambil layanan berdasarkan ID terkecil
        $layananReguler = $this->findLayanan('Cuci Reguler');
        $layananSetrika = $this->findLayanan('Cuci Setrika');

        // 5. Membuat transaksi contoh jika belum tersedia
        $transaksi = Transaksi::firstOrCreate(
            [
                'pelanggan_id' => $pelanggan->id,
                'layanan_id' => $layananReguler->id,
                'kasir_id' => $kasir->id,
                'tanggal_masuk' => '2026-09-28',
            ],
            [
                'berat_kg' => 3.50,
                'total_harga' => 24500,
                'status' => 'diterima',
                'tanggal_selesai' => null,
            ]
        );

        Transaksi::firstOrCreate(
            [
                'pelanggan_id' => $pelangganDua->id,
                'layanan_id' => $layananSetrika->id,
                'kasir_id' => $kasir->id,
                'tanggal_masuk' => '2026-10-05',
            ],
            [
                'berat_kg' => 4.00,
                'total_harga' => 32000,
                'status' => 'selesai',
                'tanggal_selesai' => '2026-10-07',
            ]
        );

        // 6. Membuat notifikasi jika belum tersedia
        Notifikasi::firstOrCreate(
            [
                'transaksi_id' => $transaksi->id,
                'pesan' => 'Transaksi laundry telah diterima.',
            ],
            [
                'tanggal_kirim' => now(),
            ]
        );
    }

    private function createPelanggan(
        string $email,
        string $nama,
        string $username,
        string $noHp,
        string $alamat
    ): Pelanggan {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $nama,
                'username' => $username,
                'role' => User::ROLE_PELANGGAN,
                'password' => Hash::make('password123'),
            ]
        );

        return Pelanggan::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $nama,
                'no_hp' => $noHp,
                'alamat' => $alamat,
            ]
        );
    }

    private function findLayanan(string $namaLayanan): Layanan
    {
        $layanan = Layanan::where('nama_layanan', $namaLayanan)
            ->orderBy('id')
            ->first();

        if (! $layanan) {
            throw new \RuntimeException("Layanan {$namaLayanan} belum tersedia.");
        }

        return $layanan;
    }
}
