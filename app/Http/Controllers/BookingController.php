<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingRequestReceivedBusiness;
use App\Mail\BookingRequestReceivedCustomer;
use App\Models\Appointment;
use App\Models\Customer;
use App\PhoneNumber;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect(route('home').'#booking')
                ->with('booking_status', 'Thank you. Your booking has been received.');
        }

        $validated = $request->validated();
        $startsAt = CarbonImmutable::createFromFormat(
            'Y-m-d\TH:i',
            $validated['starts_at'],
            config('app.timezone'),
        );
        $durationMinutes = (int) $validated['duration_minutes'];

        try {
            $appointment = Cache::lock('appointment-booking-'.$startsAt->format('Y-m-d'), 10)->block(5, function () use ($validated, $startsAt, $durationMinutes): ?Appointment {
                return DB::transaction(function () use ($validated, $startsAt, $durationMinutes): ?Appointment {
                    if (Appointment::hasConflict($startsAt, $durationMinutes)) {
                        return null;
                    }

                    $customer = Customer::query()->updateOrCreate(
                        ['email' => $validated['email']],
                        [
                            'name' => $validated['name'],
                            'phone' => PhoneNumber::normalize($validated['phone_country'], $validated['phone']),
                        ],
                    );

                    return $customer->appointments()->create([
                        'treatment_slug' => $validated['treatment_slug'],
                        'starts_at' => $startsAt,
                        'duration_minutes' => $durationMinutes,
                        'status' => Appointment::STATUS_PENDING_CONFIRMATION,
                        'notes' => $validated['message'] ?? null,
                    ]);
                });
            });
        } catch (LockTimeoutException) {
            return redirect(route('home').'#booking')
                ->withInput()
                ->withErrors(['starts_at' => 'The calendar is busy. Please choose the time again.']);
        }

        if ($appointment === null) {
            return redirect(route('home').'#booking')
                ->withInput()
                ->withErrors(['starts_at' => 'That appointment has just been taken. Please choose another time.']);
        }

        $appointment->load('customer');
        $this->queueBookingEmails($appointment);

        return redirect()->route('booking-confirmation')->with('booking_confirmation', [
            'duration' => $durationMinutes,
            'date' => $startsAt->format('l, j F'),
            'time' => $startsAt->format('g:i A'),
        ]);
    }

    private function queueBookingEmails(Appointment $appointment): void
    {
        try {
            Mail::to($appointment->customer->email)->queue(
                (new BookingRequestReceivedCustomer($appointment))->afterCommit(),
            );

            foreach (config('mail.business_recipients') as $recipient) {
                Mail::to($recipient)->queue(
                    (new BookingRequestReceivedBusiness($appointment))->afterCommit(),
                );
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
