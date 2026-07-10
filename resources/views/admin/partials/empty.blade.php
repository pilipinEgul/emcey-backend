@props(['title' => 'Nothing yet', 'description' => null, 'cta' => null])

<div class="card p-10 text-center">
    <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-blush-50 text-terracotta-500 text-xl">✦</div>
    <h3 class="mt-4 text-lg font-semibold text-ink-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-2 text-sm text-ink-500">{{ $description }}</p>
    @endif
    @if($cta)
        <div class="mt-5">{{ $cta }}</div>
    @endif
</div>
