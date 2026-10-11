@props([
    'href',
    'active' => false,
    'icon' => null,
])

<a href="{{ $href }}" @class([
    'flex items-center gap-3 rounded-lg px-3 py-2.5 transition',
    'bg-white/15 font-semibold text-white' => $active,
    'text-slate-300 hover:bg-white/10 hover:text-white' => ! $active,
])>
    @if ($icon)<i data-lucide="{{ $icon }}" class="h-4 w-4 shrink-0"></i>@endif
    <span class="truncate">{{ $slot }}</span>
</a>
