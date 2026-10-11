<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'harga_per_kg',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'layanan_id');
    }
}
