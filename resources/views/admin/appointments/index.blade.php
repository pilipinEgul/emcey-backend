@extends('admin.layout')

@section('eyebrow', 'Inbox')
@section('title', 'Appointments')

@section('content')
    <div class="mb-5 flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.appointments.index') }}" class="chip {{ ! $status ? 'chip-gold' : 'chip-muted' }}">All</a>
        @foreach($statuses as $st)
            <a href="{{ route('admin.appointments.index', ['status' => $st]) }}" class="chip {{ $status === $st ? 'chip-gold' : 'chip-muted' }}">
                {{ str_replace('_', ' ', $st) }}
            </a>
        @endforeach
    </div>

    @if($appointments->isEmpty())
        <x-admin.empty title="No appointments" description="Bookings from the public site will appear here." />
    @else
        <div class="card overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr><th>Reference</th><th>Customer</th><th>Service</th><th>Scheduled</th><th>Status</th><th>Payment</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($appointments as $a)
                        <tr>
                            <td class="font-mono text-xs">{{ $a->reference }}</td>
                            <td>
                                <div class="font-medium">{{ $a->customer_name }}</div>
                                <div class="text-xs text-ink-500">{{ $a->customer_email }} · {{ $a->customer_phone }}</div>
                            </td>
                            <td class="text-ink-500">{{ $a->service?->name ?? '—' }}</td>
                            <td>{{ $a->scheduled_at?->format('M j, Y · g:i A') }}</td>
                            <td>
                                <span class="chip {{ ['pending' => 'chip-warn', 'confirmed' => 'chip-success', 'completed' => 'chip-muted', 'cancelled' => 'chip-danger', 'no_show' => 'chip-danger'][$a->status] ?? '' }}">
                                    {{ str_replace('_', ' ', $a->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="chip {{ ['paid' => 'chip-success', 'partial' => 'chip-gold', 'unpaid' => 'chip-muted', 'refunded' => 'chip-danger'][$a->payment_status] ?? '' }}">
                                    {{ $a->payment_status }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.appointments.show', $a) }}" class="btn btn-secondary">Open</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $appointments->links() }}</div>
    @endif
@endsection
