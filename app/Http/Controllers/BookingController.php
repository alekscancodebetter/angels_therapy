<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Appointment;
use App\Models\Customer;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $durationMinutes = (int) config('booking.duration_minutes');

        try {
            $appointmentCreated = Cache::lock('appointment-booking-'.$startsAt->format('Y-m-d'), 10)->block(5, function () use ($validated, $startsAt, $durationMinutes): bool {
                return DB::transaction(function () use ($validated, $startsAt, $durationMinutes): bool {
                    if (Appointment::hasConflict($startsAt, $durationMinutes)) {
                        return false;
                    }

                    $customer = Customer::query()->updateOrCreate(
                        ['email' => $validated['email']],
                        [
                            'name' => $validated['name'],
                            'phone' => $validated['phone'],
                        ],
                    );

                    $customer->appointments()->create([
                        'treatment_slug' => $validated['treatment_slug'],
                        'starts_at' => $startsAt,
                        'duration_minutes' => $durationMinutes,
                        'status' => Appointment::STATUS_SCHEDULED,
                        'notes' => $validated['message'] ?? null,
                    ]);

                    return true;
                });
            });
        } catch (LockTimeoutException) {
            return redirect(route('home').'#booking')
                ->withInput()
                ->withErrors(['starts_at' => 'The calendar is busy. Please choose the time again.']);
        }

        if (! $appointmentCreated) {
            return redirect(route('home').'#booking')
                ->withInput()
                ->withErrors(['starts_at' => 'That appointment has just been taken. Please choose another time.']);
        }

        return redirect(route('home').'#booking')->with(
            'booking_status',
            'Your treatment is booked for '.$startsAt->format('l, j F').' at '.$startsAt->format('g:i A').'.',
        );
    }
}
