@props([
    'name',
    'label',
    'value' => null,
    'rows' => 4,
    'placeholder' => null,
    'required' => false,
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="field-label">{{ $label }}@if($required) <span class="text-terracotta-500">*</span>@endif</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'field-textarea']) }}
    >{{ old($name, $value) }}</textarea>
    @if($help)
        <p class="mt-1 text-xs text-ink-500">{{ $help }}</p>
    @endif
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
