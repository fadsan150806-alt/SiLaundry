@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-ui.hero />

    <h2 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Ringkasan</h2>
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Pelanggan" :total="$totalPelanggan" icon="users-round" accent="blue" />
        <x-ui.stat-card label="Layanan" :total="$totalLayanan" icon="washing-machine" accent="indigo" />
        <x-ui.stat-card label="Transaksi" :total="$totalTransaksi" icon="receipt-text" accent="amber" />
        <x-ui.stat-card label="Notifikasi" :total="$totalNotifikasi" icon="bell" accent="brand" />
    </div>

    @include('dashboard._transactions', ['title' => 'Transaksi Terbaru', 'transactions' => $transaksiTerbaru])
@endsection
