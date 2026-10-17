<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentConflictController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'starts_at' => ['required', 'date_format:Y-m-d H:i'],
            'duration_minutes' => ['required', 'integer', Rule::in(array_map('intval', array_keys(config('booking.duration_options'))))],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
        ]);

        $startsAt = CarbonImmutable::createFromFormat(
            '!Y-m-d H:i',
            $validated['starts_at'],
            config('app.timezone'),
        );
        $conflictingAppointment = Appointment::conflictingAppointment(
            $startsAt,
            (int) $validated['duration_minutes'],
            isset($validated['appointment_id']) ? (int) $validated['appointment_id'] : null,
        );

        if ($conflictingAppointment === null) {
            return response()->json([
                'available' => true,
                'message' => 'This time is available.',
                'conflict' => null,
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        $conflictingAppointment->load('customer:id,name');
        $conflictEndsAt = $conflictingAppointment->starts_at->addMinutes($conflictingAppointment->duration_minutes);

        return response()->json([
            'available' => false,
            'message' => sprintf(
                'Conflict: %s has an appointment from %s to %s.',
                $conflictingAppointment->customer->name,
                $conflictingAppointment->starts_at->format('H:i'),
                $conflictEndsAt->format('H:i'),
            ),
            'conflict' => [
                'appointment_id' => $conflictingAppointment->id,
                'customer_name' => $conflictingAppointment->customer->name,
                'starts_at' => $conflictingAppointment->starts_at->format('Y-m-d H:i'),
                'ends_at' => $conflictEndsAt->format('Y-m-d H:i'),
            ],
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
