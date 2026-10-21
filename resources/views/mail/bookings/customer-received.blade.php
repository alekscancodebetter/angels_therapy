<x-mail::message>
# We received your appointment request

Hello {{ $appointment->customer->name }},

Thank you for requesting an appointment with Angel Touch Therapy. Your preferred time is being held while Villy reviews the request.

**Treatment:** {{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage treatment') }}  
**Date:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('l, j F Y') }}  
**Time:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('g:i A') }}  
**Duration:** {{ $appointment->duration_minutes }} minutes

You will receive another email when the appointment is confirmed. If you need to make a change, reply to this email or call 07915 910 033.

<x-mail::button :url="route('home')">
Visit Angel Touch Therapy
</x-mail::button>

Thanks,<br>
Villy at {{ config('app.name') }}
</x-mail::message>
