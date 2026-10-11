@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-ui.hero>
        <x-ui.button variant="secondary" :href="route('users.create')">Tambah Pengguna</x-ui.button>
    </x-ui.hero>

    <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Ringkasan</h2>
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Total Pengguna" :total="$totalPengguna" icon="users" accent="brand" :href="route('users.index')" />
        <x-ui.stat-card label="Pelanggan" :total="$totalPelanggan" icon="users-round" accent="blue" />
        <x-ui.stat-card label="Layanan" :total="$totalLayanan" icon="washing-machine" accent="indigo" />
        <x-ui.stat-card label="Transaksi" :total="$totalTransaksi" icon="receipt-text" accent="amber" />
    </div>

    <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Pengguna per Peran</h2>
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <x-ui.stat-card label="Administrator" :total="$penggunaPerRole['admin'] ?? 0" icon="shield-check" accent="purple"
                        :href="route('users.index', ['role' => 'admin'])" />
        <x-ui.stat-card label="Kasir" :total="$penggunaPerRole['kasir'] ?? 0" icon="user-check" accent="blue"
                        :href="route('users.index', ['role' => 'kasir'])" />
        <x-ui.stat-card label="Pelanggan" :total="$penggunaPerRole['pelanggan'] ?? 0" icon="user-round" accent="emerald"
                        :href="route('users.index', ['role' => 'pelanggan'])" />
    </div>

    @include('dashboard._transactions', ['title' => 'Transaksi Terbaru', 'transactions' => $transaksiTerbaru])
@endsection
