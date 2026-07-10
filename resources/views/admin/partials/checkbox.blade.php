@props([
    'name',
    'label',
    'checked' => false,
    'description' => null,
])

<label class="flex items-start gap-3 rounded-xl border border-nude-100 bg-cream-50/60 px-3.5 py-3 cursor-pointer hover:border-terracotta-400/40 transition">
    <input type="hidden" name="{{ $name }}" value="0">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        class="mt-0.5 h-4 w-4 rounded border-nude-300 text-terracotta-500 focus:ring-terracotta-400"
    >
    <span class="flex-1">
        <span class="block text-sm font-medium text-ink-900">{{ $label }}</span>
        @if($description)
            <span class="block text-xs text-ink-500 mt-0.5">{{ $description }}</span>
        @endif
    </span>
</label>
