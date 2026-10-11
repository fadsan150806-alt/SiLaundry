<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-800 antialiased" x-data="{ mobile: false }">
@php
    $currentUser = auth()->user();
    $homeRoute = $currentUser->dashboardRoute() ?? 'home';
@endphp
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside x-cloak
           :class="mobile ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 flex w-64 transform flex-col bg-gradient-to-b from-brand-900 to-brand-950 text-slate-200 transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        {{-- Brand --}}
        <div class="flex h-16 items-center justify-between px-5">
            <a href="{{ route($homeRoute) }}" class="flex items-center gap-2.5">
                <img src="{{ asset('img/logo.png') }}" alt="Logo SiLaundry" class="h-9 w-9 shrink-0 object-contain">
                <span class="text-lg font-bold text-white">{{ config('app.name') }}</span>
            </a>
            <button @click="mobile = false" class="text-slate-400 lg:hidden" aria-label="Tutup menu">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        @include('partials.sidebar')

        {{-- Pengguna aktif --}}
        <div class="border-t border-white/10 p-3" x-data="{ open: false }">
            <div x-show="open" x-cloak @click.outside="open = false" class="mb-2 rounded-lg bg-white/5 p-1 text-sm">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-2 rounded px-3 py-2 text-left text-slate-200 hover:bg-white/10">
                        <i data-lucide="log-out" class="h-4 w-4"></i>Keluar
                    </button>
                </form>
            </div>
            <button @click="open = !open" class="flex w-full items-center gap-3 rounded-lg px-2 py-1.5 hover:bg-white/10">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold text-white">
                    {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                </span>
                <span class="min-w-0 flex-1 text-left">
                    <span class="block truncate font-semibold text-white">{{ $currentUser->name }}</span>
                    <span class="block truncate text-xs text-slate-400">{{ $currentUser->roleLabel() }}</span>
                </span>
                <i data-lucide="chevron-up" class="h-4 w-4 text-slate-400"></i>
            </button>
        </div>
    </aside>

    {{-- Backdrop mobile --}}
    <div x-show="mobile" x-cloak @click="mobile = false" class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"></div>

    {{-- Konten --}}
    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Header (mobile) --}}
        <header class="sticky top-0 z-30 flex items-center gap-3 border-b border-slate-200 bg-white px-4 py-3 lg:hidden">
            <button @click="mobile = true" class="text-brand-800" aria-label="Buka menu">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </button>
            <img src="{{ asset('img/logo.png') }}" alt="Logo SiLaundry" class="h-7 w-7 object-contain">
            <span class="font-bold text-brand-900">{{ config('app.name') }}</span>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
