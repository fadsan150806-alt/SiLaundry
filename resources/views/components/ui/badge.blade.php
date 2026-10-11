@props([
    'color' => 'gray',   // gray | green | red | yellow | blue | purple
])

@php
    $map = [
        'gray' => 'bg-slate-100 text-slate-700',
        'green' => 'bg-brand-100 text-brand-800',
        'red' => 'bg-red-100 text-red-700',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'blue' => 'bg-blue-100 text-blue-700',
        'purple' => 'bg-purple-100 text-purple-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ' . ($map[$color] ?? $map['gray'])]) }}>
    {{ $slot }}
</span>
