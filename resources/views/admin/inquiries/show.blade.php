@extends('admin.layout')

@section('eyebrow', 'Inbox')
@section('title', 'Inquiry from ' . $inquiry->name)

@section('actions')
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-6">
                <div class="flex items-start gap-3">
                    <span aria-hidden class="grid h-12 w-12 place-items-center rounded-full bg-gradient-to-br from-blush-200 to-nude-200 text-sm font-semibold text-terracotta-600 shrink-0">
                        {{ strtoupper(substr($inquiry->name, 0, 2)) }}
                    </span>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold">{{ $inquiry->name }}</h2>
                        <div class="text-sm text-ink-500">
                            <a href="mailto:{{ $inquiry->email }}" class="text-terracotta-500 hover:underline">{{ $inquiry->email }}</a>
                            @if($inquiry->phone) · <a href="tel:{{ $inquiry->phone }}" class="text-terracotta-500 hover:underline">{{ $inquiry->phone }}</a>@endif
                        </div>
                        <div class="text-xs text-ink-500 mt-1">Received {{ $inquiry->created_at?->format('M j, Y · g:i A') }} · Source: {{ $inquiry->source ?? '—' }}</div>
                    </div>
                </div>

                <div class="mt-5 border-t border-nude-100 pt-5">
                    @if($inquiry->subject)
                        <div class="eyebrow">Subject</div>
                        <h3 class="mt-2 text-lg font-semibold">{{ $inquiry->subject }}</h3>
                    @endif
                    <div class="eyebrow {{ $inquiry->subject ? 'mt-5' : '' }}">Message</div>
                    <p class="mt-2 text-sm whitespace-pre-line leading-relaxed text-ink-700">{{ $inquiry->message }}</p>
                </div>

                <div class="mt-6 flex flex-wrap gap-2 border-t border-nude-100 pt-5">
                    <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->subject ?? 'Your inquiry' }}" class="btn btn-primary">Reply by email</a>
                    @if($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}" class="btn btn-secondary">Call</a>
                    @endif
                </div>
            </div>
        </div>

        <aside class="space-y-5">
            <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" class="card p-6 space-y-4">
                @csrf @method('PATCH')
                <x-admin.select name="status" label="Status" :value="$inquiry->status" :options="['new' => 'New', 'in_progress' => 'In progress', 'closed' => 'Closed']" />
                <button type="submit" class="btn btn-primary w-full">Update status</button>
            </form>
        </aside>
    </div>
@endsection
