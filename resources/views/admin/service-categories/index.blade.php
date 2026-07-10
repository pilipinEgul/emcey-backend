@extends('admin.layout')

@section('eyebrow', 'Catalog')
@section('title', 'Service categories')

@section('actions')
    <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary">+ New category</a>
@endsection

@section('content')
    @if($categories->isEmpty())
        <x-admin.empty
            title="No categories yet"
            description="Group your treatments under categories like 'Brows', 'Lashes', or 'Facials'."
        >
            <x-slot:cta>
                <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary">Create your first category</a>
            </x-slot:cta>
        </x-admin.empty>
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $c)
                        <tr>
                            <td>
                                <div class="font-medium text-ink-900">{{ $c->name }}</div>
                                @if($c->description)
                                    <div class="text-xs text-ink-500 line-clamp-1 max-w-md">{{ $c->description }}</div>
                                @endif
                            </td>
                            <td class="text-ink-500 text-xs">{{ $c->slug }}</td>
                            <td>{{ $c->sort_order }}</td>
                            <td>
                                <span class="chip {{ $c->is_active ? 'chip-success' : 'chip-muted' }}">
                                    {{ $c->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.service-categories.edit', $c) }}" class="btn btn-secondary">Edit</a>
                                <x-admin.delete-button :action="route('admin.service-categories.destroy', $c)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    @endif
@endsection
