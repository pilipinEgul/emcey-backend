@extends('admin.layout')

@section('eyebrow', 'Social proof')
@section('title', 'Testimonials')

@section('actions')
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">+ New testimonial</a>
@endsection

@section('content')
    @if($testimonials->isEmpty())
        <x-admin.empty title="No testimonials yet" description="Collect Google reviews, Facebook recommendations, and direct client praise.">
            <x-slot:cta><a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Add testimonial</a></x-slot:cta>
        </x-admin.empty>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($testimonials as $t)
                <div class="card p-5 relative">
                    <span aria-hidden class="absolute right-4 top-2 font-serif text-5xl text-blush-200/70 leading-none">"</span>
                    <div class="flex items-center gap-2">
                        <div class="text-gold-500">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</div>
                        @if($t->is_featured)<span class="chip chip-gold">★ Featured</span>@endif
                        @if(! $t->is_published)<span class="chip chip-muted">Hidden</span>@endif
                    </div>
                    <p class="mt-3 text-sm text-ink-700 line-clamp-4">{{ $t->quote }}</p>
                    <div class="mt-4 flex items-end justify-between border-t border-nude-100 pt-4 text-sm">
                        <div>
                            <div class="font-medium">{{ $t->client_name }}</div>
                            <div class="text-xs text-ink-500">{{ $t->client_title ?? $t->service?->name ?? '—' }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-secondary">Edit</a>
                            <x-admin.delete-button :action="route('admin.testimonials.destroy', $t)" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-5">{{ $testimonials->links() }}</div>
    @endif
@endsection
