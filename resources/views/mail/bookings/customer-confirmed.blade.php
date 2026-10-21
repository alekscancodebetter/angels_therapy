<x-mail::message>
# Your appointment is confirmed

Hello {{ $appointment->customer->name }},

Your appointment with Angel Touch Therapy has been confirmed.

**Treatment:** {{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage treatment') }}  
**Date:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('l, j F Y') }}  
**Time:** {{ $appointment->starts_at->timezone(config('app.timezone'))->format('g:i A') }}  
**Duration:** {{ $appointment->duration_minutes }} minutes

If you need to make a change, reply to this email or call 07915 910 033.

<x-mail::button :url="route('home')">
View practice information
</x-mail::button>

Thanks,<br>
Villy at {{ config('app.name') }}
</x-mail::message>
