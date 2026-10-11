<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard sesuai role-nya.
     */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $dashboardRoute = $user->dashboardRoute();

        if ($dashboardRoute === null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Role pengguna tidak dikenali. Hubungi administrator.']);
        }

        return redirect()->route($dashboardRoute);
    }

    public function admin(): View
    {
        $totalPengguna = User::count();
        $penggunaPerRole = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $totalPelanggan = Pelanggan::count();
        $totalLayanan = Layanan::count();
        $totalTransaksi = Transaksi::count();

        $transaksiTerbaru = Transaksi::with([
            'pelanggan',
            'layanan',
            'kasir',
        ])->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'totalPengguna',
            'penggunaPerRole',
            'totalPelanggan',
            'totalLayanan',
            'totalTransaksi',
            'transaksiTerbaru'
        ));
    }

    public function kasir(): View
    {
        $totalPelanggan = Pelanggan::count();
        $totalLayanan = Layanan::count();
        $totalTransaksi = Transaksi::count();
        $totalNotifikasi = Notifikasi::count();

        $transaksiTerbaru = Transaksi::with([
            'pelanggan',
            'layanan',
            'kasir',
        ])->latest()->take(5)->get();

        return view('dashboard.kasir', compact(
            'totalPelanggan',
            'totalLayanan',
            'totalTransaksi',
            'totalNotifikasi',
            'transaksiTerbaru'
        ));
    }

    public function pelanggan(): View
    {
        $pelanggan = Pelanggan::where(
            'user_id',
            auth()->id()
        )->first();

        $totalTransaksi = 0;
        $totalNotifikasi = 0;
        $transaksiTerbaru = collect();

        if ($pelanggan) {
            $queryTransaksi = Transaksi::where(
                'pelanggan_id',
                $pelanggan->id
            );

            $totalTransaksi = (clone $queryTransaksi)->count();

            $transaksiTerbaru = (clone $queryTransaksi)
                ->with(['layanan', 'kasir'])
                ->latest()
                ->take(5)
                ->get();

            $totalNotifikasi = Notifikasi::whereIn(
                'transaksi_id',
                (clone $queryTransaksi)->select('id')
            )->count();
        }

        return view('dashboard.pelanggan', compact(
            'pelanggan',
            'totalTransaksi',
            'totalNotifikasi',
            'transaksiTerbaru'
        ));
    }
}
