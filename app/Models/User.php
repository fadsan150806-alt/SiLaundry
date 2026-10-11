<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'username',
    'role',
    'password',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_KASIR = 'kasir';

    public const ROLE_PELANGGAN = 'pelanggan';

    /**
     * Daftar role yang dikenali sistem beserta label tampilannya.
     *
     * @return array<string, string>
     */
    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_KASIR => 'Kasir',
            self::ROLE_PELANGGAN => 'Pelanggan',
        ];
    }

    /**
     * Nama route dashboard sesuai role, atau null jika role tidak dikenali.
     */
    public function dashboardRoute(): ?string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'dashboard.admin',
            self::ROLE_KASIR => 'dashboard.kasir',
            self::ROLE_PELANGGAN => 'dashboard.pelanggan',
            default => null,
        };
    }

    /**
     * Label role yang mudah dibaca.
     */
    public function roleLabel(): string
    {
        return self::roles()[$this->role] ?? 'Pengguna';
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Relasi User dengan Pelanggan.
     * Satu akun User dapat memiliki maksimal satu data Pelanggan.
     */
    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'user_id');
    }

    /**
     * Relasi User dengan Transaksi sebagai kasir.
     * Satu User dapat menangani banyak transaksi.
     */
    public function transaksiSebagaiKasir(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'kasir_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
