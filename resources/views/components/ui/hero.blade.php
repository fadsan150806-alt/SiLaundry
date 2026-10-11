@php
    $hour = (int) now()->format('H');
    $greeting = match (true) {
        $hour < 11 => 'Selamat pagi',
        $hour < 15 => 'Selamat siang',
        $hour < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-gradient-to-r from-brand-800 to-brand-950 px-6 py-5 text-white shadow-sm">
    <h1 class="text-xl font-bold">{{ $greeting }}, {{ auth()->user()->name }}</h1>
    <div class="flex items-center gap-2">
        <span class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-medium text-white ring-1 ring-white/20">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
        {{ $slot }}
    </div>
</div>
