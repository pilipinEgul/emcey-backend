@extends('admin.layout')

@section('eyebrow', 'Studio overview')
@section('title', 'Welcome back, ' . (auth()->user()->name ?? 'friend'))

@section('content')
    @php
        $toneClasses = [
            'terracotta' => 'from-terracotta-300/40 to-terracotta-400/30 text-terracotta-600',
            'gold' => 'from-gold-400/40 to-gold-500/20 text-gold-600',
            'blush' => 'from-blush-200/60 to-blush-300/30 text-terracotta-600',
            'nude' => 'from-nude-200/70 to-nude-300/40 text-ink-700',
        ];
    @endphp

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
        @foreach($stats as $s)
            <a href="{{ route($s['route']) }}" class="card p-5 group transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-gradient-to-br {{ $toneClasses[$s['tone']] ?? '' }} opacity-70 blur-xl"></div>
                    <div class="relative">
                        <div class="text-xs uppercase tracking-[0.22em] text-ink-500">{{ $s['label'] }}</div>
                        <div class="mt-2 font-semibold text-3xl text-ink-900">{{ number_format($s['value']) }}</div>
                        <div class="mt-3 text-xs text-terracotta-500 opacity-0 transition group-hover:opacity-100">View →</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Recent activity --}}
    <div class="mt-8 grid gap-5 lg:grid-cols-2">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="eyebrow">Recent</div>
                    <h2 class="mt-1 text-lg font-semibold text-ink-900">Appointments</h2>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="text-sm text-terracotta-500 hover:underline">All →</a>
            </div>

            @if($recentAppointments->isEmpty())
                <p class="mt-6 text-sm text-ink-500">No appointments yet.</p>
            @else
                <ul class="mt-4 divide-y divide-nude-100">
                    @foreach($recentAppointments as $a)
                        <li class="py-3 flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.appointments.show', $a) }}" class="font-medium text-ink-900 hover:text-terracotta-500">
                                    {{ $a->customer_name }}
                                </a>
                                <div class="text-xs text-ink-500 truncate">
                                    {{ $a->service?->name ?? 'No service' }} · {{ $a->scheduled_at?->format('M j, Y · g:i A') }}
                                </div>
                            </div>
                            <span class="chip {{ ['pending' => 'chip-warn', 'confirmed' => 'chip-success', 'completed' => 'chip-muted', 'cancelled' => 'chip-danger', 'no_show' => 'chip-danger'][$a->status] ?? '' }}">
                                {{ str_replace('_', ' ', $a->status) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="eyebrow">Recent</div>
                    <h2 class="mt-1 text-lg font-semibold text-ink-900">Inquiries</h2>
                </div>
                <a href="{{ route('admin.inquiries.index') }}" class="text-sm text-terracotta-500 hover:underline">All →</a>
            </div>

            @if($recentInquiries->isEmpty())
                <p class="mt-6 text-sm text-ink-500">No inquiries yet.</p>
            @else
                <ul class="mt-4 divide-y divide-nude-100">
                    @foreach($recentInquiries as $i)
                        <li class="py-3 flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.inquiries.show', $i) }}" class="font-medium text-ink-900 hover:text-terracotta-500">
                                    {{ $i->name }}
                                </a>
                                <div class="text-xs text-ink-500 truncate">
                                    {{ $i->subject ?? 'General' }} · {{ $i->created_at?->diffForHumans() }}
                                </div>
                            </div>
                            <span class="chip {{ ['new' => 'chip-warn', 'in_progress' => 'chip-gold', 'closed' => 'chip-muted'][$i->status] ?? '' }}">
                                {{ str_replace('_', ' ', $i->status) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
