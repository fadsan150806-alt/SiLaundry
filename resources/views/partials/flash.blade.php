@foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $key => $color)
    @if (session($key))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             @class([
                'mb-5 flex items-start justify-between gap-3 rounded-lg border px-4 py-3 text-sm',
                'border-brand-200 bg-brand-50 text-brand-800' => $color === 'green',
                'border-red-200 bg-red-50 text-red-800' => $color === 'red',
                'border-yellow-200 bg-yellow-50 text-yellow-800' => $color === 'yellow',
             ])>
            <span>{{ session($key) }}</span>
            <button @click="show = false" class="text-current/60 hover:text-current" aria-label="Tutup">&times;</button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-medium">Periksa kembali isian berikut:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
