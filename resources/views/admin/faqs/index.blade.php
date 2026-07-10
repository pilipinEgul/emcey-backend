@extends('admin.layout')

@section('eyebrow', 'Support')
@section('title', 'FAQs')

@section('actions')
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ New FAQ</a>
@endsection

@section('content')
    @if($faqs->isEmpty())
        <x-admin.empty title="No FAQs yet" description="Help clients with quick answers about treatments, pricing, and aftercare.">
            <x-slot:cta><a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">Add FAQ</a></x-slot:cta>
        </x-admin.empty>
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr><th>Question</th><th>Service / category</th><th>Order</th><th>Status</th><th class="text-right">Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($faqs as $f)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $f->question }}</div>
                                <div class="text-xs text-ink-500 line-clamp-1 max-w-md">{{ $f->answer }}</div>
                            </td>
                            <td class="text-ink-500">{{ $f->service?->name ?? $f->category ?? 'General' }}</td>
                            <td>{{ $f->sort_order }}</td>
                            <td><span class="chip {{ $f->is_active ? 'chip-success' : 'chip-muted' }}">{{ $f->is_active ? 'Active' : 'Hidden' }}</span></td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.faqs.edit', $f) }}" class="btn btn-secondary">Edit</a>
                                <x-admin.delete-button :action="route('admin.faqs.destroy', $f)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $faqs->links() }}</div>
    @endif
@endsection
