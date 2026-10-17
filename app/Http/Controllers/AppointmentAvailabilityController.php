<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AppointmentAvailabilityController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'duration_minutes' => ['nullable', 'integer', Rule::in(array_keys(config('booking.duration_options')))],
        ]);
        $durationMinutes = (int) ($validated['duration_minutes'] ?? config('booking.duration_minutes'));
        $month = CarbonImmutable::createFromFormat('Y-m', $validated['month'], config('app.timezone'))->startOfMonth();
        $currentMonth = CarbonImmutable::now(config('app.timezone'))->startOfMonth();
        $lastMonth = CarbonImmutable::now(config('app.timezone'))
            ->addDays((int) config('booking.booking_window_days'))
            ->startOfMonth();

        if ($month->lt($currentMonth) || $month->gt($lastMonth)) {
            throw ValidationException::withMessages([
                'month' => 'Choose a month inside the available booking window.',
            ]);
        }

        return response()->json([
            'month' => $month->format('Y-m'),
            'label' => $month->format('F Y'),
            'previous_month' => $month->gt($currentMonth) ? $month->subMonth()->format('Y-m') : null,
            'next_month' => $month->lt($lastMonth) ? $month->addMonth()->format('Y-m') : null,
            'duration_minutes' => $durationMinutes,
            'days' => Appointment::availableSlotsForMonth($month, $durationMinutes),
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
