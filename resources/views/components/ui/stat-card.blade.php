@props([
    'label',
    'total',
    'icon' => 'circle',
    'accent' => 'slate',
    'href' => null,
])

@php
    $palette = [
        'brand' => 'bg-brand-50 text-brand-700',
        'blue' => 'bg-blue-50 text-blue-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'slate' => 'bg-slate-100 text-slate-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'red' => 'bg-red-50 text-red-600',
    ];
    $classes = $palette[$accent] ?? $palette['slate'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    class="group flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition {{ $href ? 'hover:border-brand-200 hover:shadow' : '' }}">
    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $classes }}">
        <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
    </span>
    <div class="min-w-0 flex-1">
        <p class="truncate text-xs uppercase tracking-wider text-slate-500">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-900">{{ is_numeric($total) ? number_format((int) $total, 0, ',', '.') : $total }}</p>
    </div>
</{{ $tag }}>
