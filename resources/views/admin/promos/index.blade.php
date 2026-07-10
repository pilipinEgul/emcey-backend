@extends('admin.layout')

@section('eyebrow', 'Marketing')
@section('title', 'Promos')

@section('actions')
    <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">+ New promo</a>
@endsection

@section('content')
    @if($promos->isEmpty())
        <x-admin.empty title="No promos yet" description="Run seasonal discounts and feature them on the homepage.">
            <x-slot:cta><a href="{{ route('admin.promos.create') }}" class="btn btn-primary">Create promo</a></x-slot:cta>
        </x-admin.empty>
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr><th>Code · Title</th><th>Value</th><th>Window</th><th>Usage</th><th>Status</th><th class="text-right">Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($promos as $p)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="chip">{{ $p->code }}</span>
                                    <span class="font-medium">{{ $p->title }}</span>
                                    @if($p->is_featured)<span class="chip chip-gold">★ Featured</span>@endif
                                </div>
                            </td>
                            <td>
                                @if($p->type === 'percentage')
                                    {{ rtrim(rtrim(number_format($p->value, 2), '0'), '.') }}%
                                @else
                                    ₱{{ number_format($p->value, 0) }}
                                @endif
                            </td>
                            <td class="text-xs text-ink-500">
                                {{ $p->starts_at?->format('M j') ?? '—' }} → {{ $p->ends_at?->format('M j, Y') ?? '∞' }}
                            </td>
                            <td>{{ $p->used_count }}{{ $p->usage_limit ? ' / ' . $p->usage_limit : '' }}</td>
                            <td><span class="chip {{ $p->is_active ? 'chip-success' : 'chip-muted' }}">{{ $p->is_active ? 'Active' : 'Off' }}</span></td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.promos.edit', $p) }}" class="btn btn-secondary">Edit</a>
                                <x-admin.delete-button :action="route('admin.promos.destroy', $p)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $promos->links() }}</div>
    @endif
@endsection
