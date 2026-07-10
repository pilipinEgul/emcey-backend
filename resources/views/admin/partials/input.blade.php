@props([
    'name',
    'label',
    'value' => null,
    'type' => 'text',
    'placeholder' => null,
    'required' => false,
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="field-label">{{ $label }}@if($required) <span class="text-terracotta-500">*</span>@endif</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'field-input']) }}
    >
    @if($help)
        <p class="mt-1 text-xs text-ink-500">{{ $help }}</p>
    @endif
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
