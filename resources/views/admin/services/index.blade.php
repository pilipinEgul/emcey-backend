@extends('admin.layout')

@section('eyebrow', 'Catalog')
@section('title', 'Services')

@section('actions')
    <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">Categories</a>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ New service</a>
@endsection

@section('content')
    <form method="GET" class="mb-5 flex items-center gap-2 max-w-md">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search services…" class="field-input">
        <button class="btn btn-secondary">Search</button>
    </form>

    @if($services->isEmpty())
        <x-admin.empty
            title="No services yet"
            description="Add your first treatment — microblading, lash extensions, facials, anything you offer."
        >
            <x-slot:cta>
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Create your first service</a>
            </x-slot:cta>
        </x-admin.empty>
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $s)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="font-medium text-ink-900">{{ $s->name }}</div>
                                    @if($s->is_featured)
                                        <span class="chip chip-gold">★ Featured</span>
                                    @endif
                                </div>
                                @if($s->short_description)
                                    <div class="text-xs text-ink-500 line-clamp-1 max-w-md mt-0.5">{{ $s->short_description }}</div>
                                @endif
                            </td>
                            <td class="text-ink-500">{{ $s->category?->name ?? '—' }}</td>
                            <td>
                                @if($s->price)
                                    <div class="font-medium">₱{{ number_format($s->price, 0) }}</div>
                                @endif
                                @if($s->promo_price)
                                    <div class="text-xs text-terracotta-500">Promo ₱{{ number_format($s->promo_price, 0) }}</div>
                                @endif
                            </td>
                            <td>{{ $s->duration_minutes ? $s->duration_minutes . ' min' : '—' }}</td>
                            <td>
                                <span class="chip {{ $s->is_active ? 'chip-success' : 'chip-muted' }}">
                                    {{ $s->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.services.edit', $s) }}" class="btn btn-secondary">Edit</a>
                                <x-admin.delete-button :action="route('admin.services.destroy', $s)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $services->links() }}</div>
    @endif
@endsection
