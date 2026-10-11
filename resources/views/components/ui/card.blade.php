@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if ($title || isset($actions))
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            @if ($title)<h3 class="font-semibold text-slate-900">{{ $title }}</h3>@endif
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ isset($flush) ? '' : 'p-5' }}">
        {{ $slot }}
    </div>
</div>
