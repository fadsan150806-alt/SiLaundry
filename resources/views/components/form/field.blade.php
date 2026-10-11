@props([
    'name',
    'label' => null,
    'required' => false,
    'stacked' => false, // true: label di atas input (mis. form login yang sempit)
])

@if ($stacked)
    <div class="space-y-1.5">
        @if ($label)
            <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
                {{ $label }}
                @if ($required)<span class="text-red-500">*</span>@endif
            </label>
        @endif

        {{ $slot }}

        @error($name)<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
@else
    {{-- Baris field: label di kiri, input di kanan (bertumpuk pada layar kecil). --}}
    <div {{ $attributes->merge(['class' => 'grid grid-cols-1 gap-1.5 py-3 sm:grid-cols-[12rem_minmax(0,36rem)] sm:items-start sm:gap-5']) }}>
        @if ($label)
            <label for="{{ $name }}" class="text-sm font-medium text-slate-700 sm:pt-2">
                {{ $label }}
                @if ($required)<span class="text-red-500">*</span>@endif
            </label>
        @else
            <span class="hidden sm:block"></span>
        @endif

        <div class="space-y-1">
            {{ $slot }}
            @error($name)<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
@endif
