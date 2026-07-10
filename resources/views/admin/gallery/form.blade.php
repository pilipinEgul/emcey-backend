@extends('admin.layout')

@section('eyebrow', 'Visuals')
@section('title', $image->exists ? 'Edit image' : 'Add image')

@section('actions')
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    @php $serviceOptions = $services->pluck('name', 'id')->all(); @endphp

    <form method="POST" action="{{ $image->exists ? route('admin.gallery.update', $image) : route('admin.gallery.store') }}" class="max-w-3xl space-y-5 card p-6 sm:p-7">
        @csrf
        @if($image->exists) @method('PUT') @endif

        <x-admin.input name="image_path" label="Image URL" :value="$image->image_path" required placeholder="https://…" />

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="before_image_path" label="Before image URL" :value="$image->before_image_path" />
            <x-admin.input name="after_image_path" label="After image URL" :value="$image->after_image_path" />
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="title" label="Title" :value="$image->title" />
            <x-admin.input name="alt_text" label="Alt text (accessibility)" :value="$image->alt_text" />
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <x-admin.select name="service_id" label="Service" :value="$image->service_id" :options="$serviceOptions" placeholder="— None —" />
            <x-admin.input name="category" label="Category" :value="$image->category" placeholder="brows, lashes…" />
            <x-admin.input name="sort_order" label="Sort order" type="number" :value="$image->sort_order ?? 0" />
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
            <x-admin.checkbox name="is_active" label="Active" :checked="$image->is_active ?? true" />
            <x-admin.checkbox name="is_featured" label="Featured" :checked="$image->is_featured ?? false" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">{{ $image->exists ? 'Save changes' : 'Add image' }}</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
@endsection
