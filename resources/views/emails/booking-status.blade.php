<x-mail::message>
# Your booking is {{ ucfirst($appointment->status) }}

Hi {{ $appointment->customer_name }},

@if($appointment->status === 'confirmed')
Good news — your appointment is confirmed. We look forward to seeing you!
@elseif($appointment->status === 'cancelled')
Your appointment has been cancelled.
@if($appointment->cancellation_reason)
**Reason:** {{ $appointment->cancellation_reason }}
@endif
@elseif($appointment->status === 'completed')
Thank you for visiting us — we hope you love the results!
@else
Your booking status has been updated to **{{ $appointment->status }}**.
@endif

<x-mail::panel>
**Reference:** {{ $appointment->reference }}
**Service:** {{ $appointment->service?->name ?? 'Appointment' }}
**When:** {{ $appointment->scheduled_at?->timezone('Asia/Manila')->format('l, F j, Y · g:i A') }}
</x-mail::panel>

<x-mail::button :url="$trackUrl">
View my booking
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
