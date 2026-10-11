<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan pencarian dan filter role.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when(array_key_exists($role, User::roles()), fn ($query) => $query->where('role', $role))
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => User::roles(),
            'search' => $search,
            'selectedRole' => $role,
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'user' => new User,
            'roles' => User::roles(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
            ]);

            $this->syncPelangganProfile($user, $data);
        });

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$data['name']} berhasil ditambahkan.");
    }

    public function edit(User $user): View
    {
        $user->load('pelanggan');

        return view('users.edit', [
            'user' => $user,
            'roles' => User::roles(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $attributes = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => $data['role'],
            ];

            // Password hanya diganti bila diisi pada form edit.
            if (! empty($data['password'])) {
                $attributes['password'] = $data['password'];
            }

            $user->update($attributes);

            $this->syncPelangganProfile($user, $data);
        });

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return redirect()->route('users.index')
                ->with('error', 'Kamu tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Transaksi menyimpan kasir_id dengan restrictOnDelete, jadi kasir yang
        // sudah menangani transaksi tidak boleh dihapus agar riwayat tetap utuh.
        if ($user->transaksiSebagaiKasir()->exists()) {
            return redirect()->route('users.index')
                ->with('error', "Pengguna {$user->name} masih tercatat sebagai kasir pada transaksi, sehingga tidak dapat dihapus.");
        }

        // Data pelanggan & transaksinya ikut terhapus (cascade), jadi pelanggan
        // yang sudah memiliki riwayat transaksi juga tidak boleh dihapus.
        if ($user->pelanggan?->transaksi()->exists()) {
            return redirect()->route('users.index')
                ->with('error', "Pengguna {$user->name} masih memiliki riwayat transaksi, sehingga tidak dapat dihapus.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$name} berhasil dihapus.");
    }

    /**
     * Membuat atau memperbarui data pelanggan yang terhubung ke akun ber-role pelanggan.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncPelangganProfile(User $user, array $data): void
    {
        if ($user->role !== User::ROLE_PELANGGAN) {
            return;
        }

        $user->pelanggan()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $data['name'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
            ],
        );
    }
}
