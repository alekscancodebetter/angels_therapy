<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

#[Fillable(['customer_id', 'treatment_slug', 'starts_at', 'duration_minutes', 'status', 'notes'])]
class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    public const MAX_DURATION_MINUTES = 240;

    public const STATUS_SCHEDULED = 'scheduled';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function overlaps(CarbonInterface $startsAt, CarbonInterface $endsAt): bool
    {
        $appointmentStartsAt = $this->starts_at->toImmutable();
        $appointmentEndsAt = $appointmentStartsAt->addMinutes($this->duration_minutes);

        return $appointmentStartsAt->lt($endsAt) && $appointmentEndsAt->gt($startsAt);
    }

    public static function hasConflict(CarbonInterface $startsAt, int $durationMinutes, ?int $ignoredAppointmentId = null): bool
    {
        $slotStartsAt = CarbonImmutable::instance($startsAt);
        $slotEndsAt = $slotStartsAt->addMinutes($durationMinutes);

        $appointments = self::query()
            ->where('status', self::STATUS_SCHEDULED)
            ->when($ignoredAppointmentId, fn ($query, int $appointmentId) => $query->whereKeyNot($appointmentId))
            ->where('starts_at', '>=', $slotStartsAt->subMinutes(self::MAX_DURATION_MINUTES))
            ->where('starts_at', '<', $slotEndsAt)
            ->get(['id', 'starts_at', 'duration_minutes']);

        return self::firstConflictingAppointment($appointments, $slotStartsAt, $slotEndsAt) !== null;
    }

    public static function isBookableStart(CarbonInterface $startsAt, ?int $durationMinutes = null): bool
    {
        $slotStartsAt = CarbonImmutable::instance($startsAt)->setTimezone(config('app.timezone'));
        $now = CarbonImmutable::now(config('app.timezone'));
        $openingTime = self::timeOnDate($slotStartsAt, config('booking.opening_time'));
        $closingTime = self::timeOnDate($slotStartsAt, config('booking.closing_time'));
        $durationMinutes ??= (int) config('booking.duration_minutes');
        $intervalMinutes = (int) config('booking.interval_minutes');

        if (! in_array($slotStartsAt->dayOfWeekIso, config('booking.working_days'), true)) {
            return false;
        }

        if ($slotStartsAt->lt($now->addHours((int) config('booking.lead_time_hours')))
            || $slotStartsAt->gt($now->addDays((int) config('booking.booking_window_days'))->endOfDay())) {
            return false;
        }

        if ($slotStartsAt->lt($openingTime) || $slotStartsAt->addMinutes($durationMinutes)->gt($closingTime)) {
            return false;
        }

        return $openingTime->diffInMinutes($slotStartsAt) % $intervalMinutes === 0;
    }

    /**
     * @return array<int, array{date: string, day: int, weekday: int, label: string, slots: array<int, array{value: string, label: string, available: bool, unavailable_reason: string|null, suggested_duration_minutes: int|null}>}>
     */
    public static function availableSlotsForMonth(CarbonImmutable $month, ?int $durationMinutes = null): array
    {
        $monthStartsAt = $month->startOfMonth();
        $monthEndsAt = $month->endOfMonth();
        $durationMinutes ??= (int) config('booking.duration_minutes');
        $intervalMinutes = (int) config('booking.interval_minutes');
        $appointments = self::query()
            ->where('status', self::STATUS_SCHEDULED)
            ->where('starts_at', '>=', $monthStartsAt->startOfDay()->subMinutes(self::MAX_DURATION_MINUTES))
            ->where('starts_at', '<=', $monthEndsAt->endOfDay())
            ->orderBy('starts_at')
            ->get(['id', 'starts_at', 'duration_minutes']);
        $days = [];

        for ($day = $monthStartsAt; $day->lte($monthEndsAt); $day = $day->addDay()) {
            $slots = [];

            if (in_array($day->dayOfWeekIso, config('booking.working_days'), true)) {
                $closingTime = self::timeOnDate($day, config('booking.closing_time'));

                for (
                    $slotStartsAt = self::timeOnDate($day, config('booking.opening_time'));
                    $slotStartsAt->addMinutes($durationMinutes)->lte($closingTime);
                    $slotStartsAt = $slotStartsAt->addMinutes($intervalMinutes)
                ) {
                    $slotEndsAt = $slotStartsAt->addMinutes($durationMinutes);
                    $conflictingAppointment = self::firstConflictingAppointment($appointments, $slotStartsAt, $slotEndsAt);
                    $suggestedDuration = $conflictingAppointment === null
                        ? null
                        : self::suggestedDurationBeforeNextAppointment(
                            $conflictingAppointment,
                            $slotStartsAt,
                            $durationMinutes,
                        );

                    if (self::isBookableStart($slotStartsAt, $durationMinutes)) {
                        $slots[] = [
                            'value' => $slotStartsAt->format('Y-m-d\TH:i'),
                            'label' => $slotStartsAt->format('g:i A'),
                            'available' => $conflictingAppointment === null,
                            'unavailable_reason' => $conflictingAppointment === null
                                ? null
                                : self::unavailableReason($conflictingAppointment, $slotStartsAt, $suggestedDuration),
                            'suggested_duration_minutes' => $suggestedDuration,
                        ];
                    }
                }
            }

            $days[] = [
                'date' => $day->format('Y-m-d'),
                'day' => $day->day,
                'weekday' => $day->dayOfWeekIso,
                'label' => $day->format('l, j F'),
                'slots' => $slots,
            ];
        }

        return $days;
    }

    /** @param Collection<int, self> $appointments */
    private static function firstConflictingAppointment(
        Collection $appointments,
        CarbonInterface $startsAt,
        CarbonInterface $endsAt,
    ): ?self {
        return $appointments->first(
            fn (self $appointment): bool => $appointment->overlaps($startsAt, $endsAt),
        );
    }

    private static function unavailableReason(
        self $appointment,
        CarbonInterface $slotStartsAt,
        ?int $suggestedDuration,
    ): string {
        if ($appointment->starts_at->gt($slotStartsAt)) {
            if ($suggestedDuration !== null) {
                return 'The next appointment at '.$appointment->starts_at->format('g:i A')
                    .' is already booked, so only '.$suggestedDuration.' minutes fit here.';
            }

            return 'The next appointment at '.$appointment->starts_at->format('g:i A').' is already booked.';
        }

        if ($appointment->starts_at->equalTo($slotStartsAt)) {
            return 'This time is already booked.';
        }

        return 'Another appointment is still in progress.';
    }

    private static function suggestedDurationBeforeNextAppointment(
        self $appointment,
        CarbonInterface $slotStartsAt,
        int $requestedDurationMinutes,
    ): ?int {
        if (! $appointment->starts_at->gt($slotStartsAt)) {
            return null;
        }

        $minutesBeforeNextAppointment = (int) $slotStartsAt->diffInMinutes($appointment->starts_at, true);

        return collect(array_keys(config('booking.duration_options')))
            ->map(fn (int|string $duration): int => (int) $duration)
            ->filter(fn (int $duration): bool => $duration < $requestedDurationMinutes
                && $duration <= $minutesBeforeNextAppointment)
            ->sortDesc()
            ->first();
    }

    private static function timeOnDate(CarbonImmutable $date, string $time): CarbonImmutable
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        return $date->setTime($hours, $minutes);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'notes' => 'encrypted',
        ];
    }
}
