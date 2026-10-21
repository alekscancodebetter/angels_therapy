<x-mail::message>
# We received your message

Hello {{ $appointmentRequest->name }},

Thank you for contacting Angel Touch Therapy. Villy has received your message and will reply as soon as possible.

**Your message:**  
{{ $appointmentRequest->message }}

If your enquiry is urgent, call 07915 910 033.

<x-mail::button :url="route('home')">
Visit Angel Touch Therapy
</x-mail::button>

Thanks,<br>
Villy at {{ config('app.name') }}
</x-mail::message>
