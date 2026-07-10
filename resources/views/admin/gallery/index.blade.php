@extends('admin.layout')

@section('eyebrow', 'Visuals')
@section('title', 'Gallery')

@section('actions')
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">+ Add image</a>
@endsection

@section('content')
    @if($images->isEmpty())
        <x-admin.empty title="No gallery images yet" description="Showcase brows, lashes, and before/after results from your studio.">
            <x-slot:cta><a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Add image</a></x-slot:cta>
        </x-admin.empty>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($images as $img)
                <div class="card overflow-hidden group">
                    <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-blush-100 to-nude-100">
                        @if($img->image_path)
                            <img src="{{ $img->image_path }}" alt="{{ $img->alt_text ?? $img->title }}" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy">
                        @else
                            <div class="grid h-full w-full place-items-center text-ink-300">No image</div>
                        @endif
                        @if($img->is_featured)
                            <span class="absolute left-2 top-2 chip chip-gold shadow">★</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="text-sm font-medium truncate">{{ $img->title ?? $img->category ?? 'Untitled' }}</div>
                        <div class="text-xs text-ink-500 truncate">{{ $img->service?->name ?? '—' }}</div>
                        <div class="mt-2 flex items-center gap-1">
                            <a href="{{ route('admin.gallery.edit', $img) }}" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</a>
                            <x-admin.delete-button :action="route('admin.gallery.destroy', $img)" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-5">{{ $images->links() }}</div>
    @endif
@endsection
