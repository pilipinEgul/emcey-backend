@props([
    'name',
    'label',
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
])

<div>
    <label for="{{ $name }}" class="field-label">{{ $label }}@if($required) <span class="text-terracotta-500">*</span>@endif</label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'field-select']) }}
    >
        @if($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $key => $opt)
            <option value="{{ $key }}" @selected((string) old($name, $value) === (string) $key)>{{ $opt }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>
