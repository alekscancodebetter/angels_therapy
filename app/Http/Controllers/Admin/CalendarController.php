<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $month = isset($validated['month'])
            ? CarbonImmutable::createFromFormat('Y-m', $validated['month'])->startOfMonth()
            : CarbonImmutable::now()->startOfMonth();

        $gridStart = $month->startOfWeek(CarbonImmutable::MONDAY);
        $gridEnd = $month->endOfMonth()->endOfWeek(CarbonImmutable::SUNDAY);

        $appointments = Appointment::query()
            ->with('customer')
            ->whereBetween('starts_at', [$gridStart->startOfDay(), $gridEnd->endOfDay()])
            ->orderBy('starts_at')
            ->get()
            ->groupBy(fn (Appointment $appointment): string => $appointment->starts_at->format('Y-m-d'));

        return view('admin.calendar', [
            'month' => $month,
            'days' => $this->calendarDays($gridStart, $gridEnd),
            'appointments' => $appointments,
        ]);
    }

    /** @return Collection<int, CarbonImmutable> */
    private function calendarDays(CarbonImmutable $start, CarbonImmutable $end): Collection
    {
        $days = collect();

        for ($day = $start; $day->lte($end); $day = $day->addDay()) {
            $days->push($day);
        }

        return $days;
    }
}
