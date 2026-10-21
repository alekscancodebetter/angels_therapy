<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentRequestReceivedBusiness;
use App\Mail\AppointmentRequestReceivedCustomer;
use App\Models\AppointmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AppointmentRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return back()->with('status', 'Thank you. Your message has been received.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $appointmentRequest = AppointmentRequest::create($validated);
        $this->queueAppointmentRequestEmails($appointmentRequest);

        return back()->with('status', 'Thank you. Your appointment request has been received.');
    }

    private function queueAppointmentRequestEmails(AppointmentRequest $appointmentRequest): void
    {
        try {
            Mail::to($appointmentRequest->email)->queue(
                (new AppointmentRequestReceivedCustomer($appointmentRequest))->afterCommit(),
            );

            foreach (config('mail.business_recipients') as $recipient) {
                Mail::to($recipient)->queue(
                    (new AppointmentRequestReceivedBusiness($appointmentRequest))->afterCommit(),
                );
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
