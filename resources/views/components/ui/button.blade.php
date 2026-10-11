@props([
    'variant' => 'primary',   // primary | secondary | danger
    'href' => null,           // jika diisi, render sebagai <a>
    'type' => 'button',
])

@php
    $classes = 'btn btn-' . $variant;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
