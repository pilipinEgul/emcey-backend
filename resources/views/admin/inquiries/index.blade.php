@extends('admin.layout')

@section('eyebrow', 'Inbox')
@section('title', 'Contact inquiries')

@section('content')
    <div class="mb-5 flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.inquiries.index') }}" class="chip {{ ! $status ? 'chip-gold' : 'chip-muted' }}">All</a>
        @foreach($statuses as $st)
            <a href="{{ route('admin.inquiries.index', ['status' => $st]) }}" class="chip {{ $status === $st ? 'chip-gold' : 'chip-muted' }}">
                {{ str_replace('_', ' ', $st) }}
            </a>
        @endforeach
    </div>

    @if($inquiries->isEmpty())
        <x-admin.empty title="No inquiries yet" description="Messages from the contact form will appear here." />
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr><th>From</th><th>Subject</th><th>Received</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $i)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $i->name }}</div>
                                <div class="text-xs text-ink-500">{{ $i->email }}{{ $i->phone ? ' · ' . $i->phone : '' }}</div>
                            </td>
                            <td>
                                <div>{{ $i->subject ?? 'General inquiry' }}</div>
                                <div class="text-xs text-ink-500 line-clamp-1 max-w-md">{{ $i->message }}</div>
                            </td>
                            <td class="text-xs text-ink-500">{{ $i->created_at?->diffForHumans() }}</td>
                            <td>
                                <span class="chip {{ ['new' => 'chip-warn', 'in_progress' => 'chip-gold', 'closed' => 'chip-muted'][$i->status] ?? '' }}">
                                    {{ str_replace('_', ' ', $i->status) }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.inquiries.show', $i) }}" class="btn btn-secondary">Open</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $inquiries->links() }}</div>
    @endif
@endsection
