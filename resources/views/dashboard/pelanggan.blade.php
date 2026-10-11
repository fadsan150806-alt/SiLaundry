@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-ui.hero />

    @if (! $pelanggan)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Akun belum terhubung dengan data pelanggan.
        </div>
    @endif

    <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Ringkasan</h2>
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Transaksi Saya" :total="$totalTransaksi" icon="receipt-text" accent="amber" />
        <x-ui.stat-card label="Notifikasi" :total="$totalNotifikasi" icon="bell" accent="brand" />
    </div>

    @include('dashboard._transactions', [
        'title' => 'Riwayat Laundry',
        'transactions' => $transaksiTerbaru,
        'showPelanggan' => false,
    ])
@endsection
