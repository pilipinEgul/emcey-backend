@extends('admin.layout')

@section('eyebrow', 'Social proof')
@section('title', $testimonial->exists ? 'Edit testimonial' : 'New testimonial')

@section('actions')
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    @php $serviceOptions = $services->pluck('name', 'id')->all(); @endphp

    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="max-w-3xl space-y-5 card p-6 sm:p-7">
        @csrf
        @if($testimonial->exists) @method('PUT') @endif

        <x-admin.textarea name="quote" label="Quote" :value="$testimonial->quote" rows="4" required />

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="client_name" label="Client name" :value="$testimonial->client_name" required />
            <x-admin.input name="client_title" label="Title / location" :value="$testimonial->client_title" placeholder="e.g. Bride, Imus" />
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.select name="service_id" label="Service" :value="$testimonial->service_id" :options="$serviceOptions" placeholder="— None —" />
            <x-admin.select name="rating" label="Rating" :value="$testimonial->rating ?? 5" :options="[5 => '5 stars', 4 => '4 stars', 3 => '3 stars', 2 => '2 stars', 1 => '1 star']" />
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="client_avatar" label="Client photo URL" :value="$testimonial->client_avatar" placeholder="https://…" />
            <x-admin.input name="source" label="Source" :value="$testimonial->source" placeholder="Google, Facebook, IG" />
        </div>

        <x-admin.input name="video_url" label="Video URL (optional)" :value="$testimonial->video_url" placeholder="https://…" />
        <x-admin.input name="sort_order" label="Sort order" type="number" :value="$testimonial->sort_order ?? 0" />

        <div class="grid sm:grid-cols-2 gap-3">
            <x-admin.checkbox name="is_published" label="Published" :checked="$testimonial->is_published ?? true" />
            <x-admin.checkbox name="is_featured" label="Featured (homepage)" :checked="$testimonial->is_featured ?? false" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">{{ $testimonial->exists ? 'Save changes' : 'Add testimonial' }}</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
@endsection
