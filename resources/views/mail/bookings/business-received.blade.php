<x-mail::message>
# New appointment request

{{ $appointment->customer->name }} submitted a booking request through the website.

**Treatment:** {{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage treatment') }}  
**Date:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('l, j F Y') }}  
**Time:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('g:i A') }}  
**Duration:** {{ $appointment->duration_minutes }} minutes  
**Customer:** {{ $appointment->customer->name }}  
**Email:** {{ $appointment->customer->email }}  
**Telephone:** {{ $appointment->customer->phone }}

@if ($appointment->notes)
**Customer message:**  
{{ $appointment->notes }}
@endif

The time is blocked in the calendar with status **Pending confirmation**. Open the appointment in the admin panel and change its status to **Scheduled** to send the customer a confirmation email.

<x-mail::button :url="route('admin.appointments.edit', $appointment)">
Review appointment
</x-mail::button>

This notification was sent by {{ config('app.name') }}.
</x-mail::message>
