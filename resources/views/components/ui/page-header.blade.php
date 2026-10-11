@props([
    'title',
])

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <h1 class="text-2xl font-bold text-brand-900">{{ $title }}</h1>
    @isset($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
