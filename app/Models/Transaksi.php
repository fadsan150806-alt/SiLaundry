<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'pelanggan_id',
        'layanan_id',
        'kasir_id',
        'berat_kg',
        'total_harga',
        'status',
        'tanggal_masuk',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'berat_kg' => 'decimal:2',
            'total_harga' => 'decimal:2',
            'tanggal_masuk' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'transaksi_id');
    }
}
