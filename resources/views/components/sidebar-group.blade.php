@props([
    'label',
    'icon' => 'folder',
    'active' => false, // true = otomatis terbuka (ada child yang aktif)
])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="space-y-0.5">
    <button type="button" @click="open = !open"
            @class([
                'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition',
                'bg-white/15 font-semibold text-white' => $active,
                'text-slate-300 hover:bg-white/10 hover:text-white' => ! $active,
            ])>
        <i data-lucide="{{ $icon }}" class="h-4 w-4 shrink-0"></i>
        <span class="flex-1 truncate">{{ $label }}</span>
        <i data-lucide="chevron-down" class="h-4 w-4 shrink-0 transition-transform"
           :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak x-transition.duration.150ms class="ml-6 space-y-0.5 border-l border-white/10 pl-3">
        {{ $slot }}
    </div>
</div>
