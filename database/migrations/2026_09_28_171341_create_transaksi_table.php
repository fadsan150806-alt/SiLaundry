<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                ->constrained('pelanggan')
                ->cascadeOnDelete();

            $table->foreignId('layanan_id')
                ->constrained('layanan')
                ->restrictOnDelete();

            $table->foreignId('kasir_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('berat_kg', 8, 2);
            $table->decimal('total_harga', 12, 2);

            $table->string('status')->default('diterima');

            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
