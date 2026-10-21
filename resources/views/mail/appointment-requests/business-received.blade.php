<x-mail::message>
# New website enquiry

{{ $appointmentRequest->name }} submitted an enquiry through the website.

**Name:** {{ $appointmentRequest->name }}  
**Email:** {{ $appointmentRequest->email }}

**Message:**  
{{ $appointmentRequest->message }}

Reply directly to this email to contact the customer.
</x-mail::message>
