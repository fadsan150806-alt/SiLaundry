<div class="mb-6 text-center">
    <img src="{{ asset('img/logo.png') }}" alt="Logo SiLaundry" class="mx-auto mb-3 h-16 w-16 object-contain">
    <h1 class="text-xl font-bold text-slate-900">{{ config('app.name') }}</h1>
</div>

<div class="rounded-2xl bg-white p-8 text-center shadow-xl ring-1 ring-slate-100">
    <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600">
        <i data-lucide="{{ $icon }}" class="h-7 w-7"></i>
    </span>
    <p class="text-4xl font-bold text-brand-900">{{ $code }}</p>
    <h2 class="mt-1 text-lg font-semibold text-slate-900">{{ $heading }}</h2>

    @auth
        <div class="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">
            <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
            <x-ui.badge color="green">{{ auth()->user()->roleLabel() }}</x-ui.badge>
        </div>
    @endauth

    <div class="mt-6">
        <x-ui.button :href="route('home')">Kembali ke Dashboard</x-ui.button>
    </div>
</div>
