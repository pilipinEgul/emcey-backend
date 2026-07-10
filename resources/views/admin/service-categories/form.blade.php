@extends('admin.layout')

@section('eyebrow', 'Catalog')
@section('title', $category->exists ? 'Edit category' : 'New category')

@section('actions')
    <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    <form method="POST" action="{{ $category->exists ? route('admin.service-categories.update', $category) : route('admin.service-categories.store') }}" class="max-w-2xl space-y-5 card p-6 sm:p-7">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <x-admin.input name="name" label="Name" :value="$category->name" required />
        <x-admin.input name="slug" label="Slug" :value="$category->slug" placeholder="Auto-generated from name if left blank" help="URL fragment, lowercase, no spaces." />
        <x-admin.textarea name="description" label="Description" :value="$category->description" rows="3" />

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="icon" label="Icon (emoji or name)" :value="$category->icon" placeholder="✨" />
            <x-admin.input name="sort_order" label="Sort order" type="number" :value="$category->sort_order ?? 0" />
        </div>

        <x-admin.checkbox name="is_active" label="Active" description="Show this category on the public site." :checked="$category->is_active ?? true" />

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
@endsection
