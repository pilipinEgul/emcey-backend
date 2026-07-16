<x-mail::message>
# New booking request

A new booking just came in.

<x-mail::panel>
**Reference:** {{ $appointment->reference }}
**Client:** {{ $appointment->customer_name }}
**Phone:** {{ $appointment->customer_phone }}
**Email:** {{ $appointment->customer_email }}
**Service:** {{ $appointment->service?->name ?? 'Appointment' }}
**When:** {{ $appointment->scheduled_at?->timezone('Asia/Manila')->format('l, F j, Y · g:i A') }}
@if($appointment->notes)
**Notes:** {{ $appointment->notes }}
@endif
</x-mail::panel>

<x-mail::button :url="$adminUrl">
Open in admin
</x-mail::button>

Confirm it in the dashboard to add it to the studio calendar.
</x-mail::message>
