<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Customer;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.calendar');
    }

    public function create(Request $request): View
    {
        return view('admin.appointments.create', [
            'customers' => Customer::query()->orderBy('name')->get(),
            'selectedCustomerId' => $request->integer('customer') ?: null,
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $startsAt = CarbonImmutable::parse($validated['starts_at'], config('app.timezone'));

        if ($validated['status'] === Appointment::STATUS_SCHEDULED
            && Appointment::hasConflict($startsAt, (int) $validated['duration_minutes'])) {
            return back()->withInput()->withErrors([
                'starts_at' => 'This appointment overlaps another scheduled appointment.',
            ]);
        }

        $appointment = DB::transaction(function () use ($validated): Appointment {
            $customerId = $this->resolveCustomerId($validated);

            return Appointment::create($this->appointmentAttributes($validated, $customerId));
        });

        return redirect()->route('admin.calendar', [
            'month' => $appointment->starts_at->format('Y-m'),
        ])->with('status', 'Appointment scheduled.');
    }

    public function edit(Appointment $appointment): View
    {
        return view('admin.appointments.edit', [
            'appointment' => $appointment->load('customer'),
            'customers' => Customer::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validated();
        $startsAt = CarbonImmutable::parse($validated['starts_at'], config('app.timezone'));

        if ($validated['status'] === Appointment::STATUS_SCHEDULED
            && Appointment::hasConflict($startsAt, (int) $validated['duration_minutes'], $appointment->id)) {
            return back()->withInput()->withErrors([
                'starts_at' => 'This appointment overlaps another scheduled appointment.',
            ]);
        }

        $appointment = DB::transaction(function () use ($appointment, $validated): Appointment {
            $customerId = $this->resolveCustomerId($validated);
            $appointment->update($this->appointmentAttributes($validated, $customerId));

            return $appointment;
        });

        return redirect()->route('admin.calendar', [
            'month' => $appointment->starts_at->format('Y-m'),
        ])->with('status', 'Appointment updated.');
    }

    /** @param array<string, mixed> $validated */
    private function resolveCustomerId(array $validated): int
    {
        if ($validated['customer_mode'] === 'existing') {
            return (int) $validated['customer_id'];
        }

        return (int) Customer::create([
            'name' => $validated['customer_name'],
            'email' => $validated['customer_email'],
            'phone' => $validated['customer_phone'] ?? null,
        ])->getKey();
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function appointmentAttributes(array $validated, int $customerId): array
    {
        return [
            'customer_id' => $customerId,
            'treatment_slug' => $validated['treatment_slug'] ?? null,
            'starts_at' => $validated['starts_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];
    }
}
