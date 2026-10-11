@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

<x-form.field :name="$name" :label="$label" :required="$required">
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @unless ($type === 'password') value="{{ old($name, $value) }}" @endunless
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => 'form-input' . ($errors->has($name) ? ' ring-red-400' : '')]) }}
    >
</x-form.field>
