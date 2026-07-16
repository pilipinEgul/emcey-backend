<x-mail::message>
# Thanks, {{ $appointment->customer_name }}!

We’ve received your booking request. Here are the details:

<x-mail::panel>
**Reference:** {{ $appointment->reference }}
**Service:** {{ $appointment->service?->name ?? 'Appointment' }}
**When:** {{ $appointment->scheduled_at?->timezone('Asia/Manila')->format('l, F j, Y · g:i A') }}
**Status:** {{ ucfirst($appointment->status) }}
</x-mail::panel>

We’ll confirm your appointment shortly. You can view your status or cancel anytime:

<x-mail::button :url="$trackUrl">
Track my booking
</x-mail::button>

If you have any questions, just reply to this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
