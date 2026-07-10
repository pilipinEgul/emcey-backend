@extends('admin.layout')

@section('eyebrow', 'Inbox')
@section('title', 'Appointment · ' . $appointment->reference)

@section('actions')
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-6">
                <div class="eyebrow">Customer</div>
                <h2 class="mt-2 text-2xl font-semibold">{{ $appointment->customer_name }}</h2>
                <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-y-3 text-sm">
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Email</dt><dd class="mt-1"><a href="mailto:{{ $appointment->customer_email }}" class="text-terracotta-500 hover:underline">{{ $appointment->customer_email }}</a></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Phone</dt><dd class="mt-1"><a href="tel:{{ $appointment->customer_phone }}" class="text-terracotta-500 hover:underline">{{ $appointment->customer_phone }}</a></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Service</dt><dd class="mt-1">{{ $appointment->service?->name ?? '—' }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Scheduled</dt><dd class="mt-1">{{ $appointment->scheduled_at?->format('l, M j, Y · g:i A') }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Duration</dt><dd class="mt-1">{{ $appointment->duration_minutes ? $appointment->duration_minutes . ' min' : '—' }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-ink-500">Promo code</dt><dd class="mt-1">{{ $appointment->promo_code ?? '—' }}</dd></div>
                    @if($appointment->total_amount)
                        <div><dt class="text-xs uppercase tracking-wider text-ink-500">Total</dt><dd class="mt-1 font-medium">₱{{ number_format($appointment->total_amount, 2) }}</dd></div>
                    @endif
                    @if($appointment->down_payment)
                        <div><dt class="text-xs uppercase tracking-wider text-ink-500">Down payment</dt><dd class="mt-1">₱{{ number_format($appointment->down_payment, 2) }}</dd></div>
                    @endif
                </dl>

                @if($appointment->notes)
                    <div class="mt-5 border-t border-nude-100 pt-5">
                        <div class="eyebrow">Customer notes</div>
                        <p class="mt-2 text-sm whitespace-pre-line text-ink-700">{{ $appointment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <aside class="space-y-5">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="card p-6 space-y-4">
                @csrf @method('PATCH')

                <x-admin.select name="status" label="Status" :value="$appointment->status" :options="['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No-show']" />
                <x-admin.select name="payment_status" label="Payment" :value="$appointment->payment_status" :options="['unpaid' => 'Unpaid', 'partial' => 'Partial', 'paid' => 'Paid', 'refunded' => 'Refunded']" />
                <x-admin.textarea name="admin_notes" label="Internal notes" :value="$appointment->admin_notes" rows="5" />

                <button type="submit" class="btn btn-primary w-full">Save</button>
            </form>
        </aside>
    </div>
@endsection
