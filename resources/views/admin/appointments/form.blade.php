@php
    $editingAppointment = $appointment ?? null;
    $customerMode = old('customer_mode', 'existing');
    $currentCustomerId = old('customer_id', $editingAppointment->customer_id ?? $selectedCustomerId ?? '');
    $currentCustomer = $customers->firstWhere('id', (int) $currentCustomerId);
    $currentCustomerLabel = $currentCustomer
        ? collect([$currentCustomer->name, $currentCustomer->email, $currentCustomer->phone])->filter()->join(' · ')
        : '';
    $requestedAppointmentDate = request()->query('date');
    $appointmentDate = old('appointment_date', $editingAppointment?->starts_at?->format('Y-m-d') ?? (is_string($requestedAppointmentDate) ? $requestedAppointmentDate : now()->addDay()->format('Y-m-d')));
    $appointmentTime = old('appointment_time', $editingAppointment?->starts_at?->format('H:i') ?? '10:00');
    $selectedTreatment = (string) old('treatment_slug', $editingAppointment->treatment_slug ?? '');
    $treatmentOptions = collect(['' => 'General massage appointment'])
        ->merge(collect(config('treatments'))->map(fn (array $treatment): string => $treatment['title']));
    $selectedDuration = (int) old('duration_minutes', $editingAppointment->duration_minutes ?? config('booking.duration_minutes'));
    $statusOptions = ['pending_confirmation' => 'Pending confirmation', 'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No show'];
    $selectedStatus = (string) old('status', $editingAppointment->status ?? 'scheduled');
    [$openingHour, $openingMinute] = array_map('intval', explode(':', config('booking.opening_time')));
    [$closingHour, $closingMinute] = array_map('intval', explode(':', config('booking.closing_time')));
    $openingMinutes = ($openingHour * 60) + $openingMinute;
    $closingMinutes = ($closingHour * 60) + $closingMinute;
    $appointmentTimes = collect(range($openingMinutes, $closingMinutes, (int) config('booking.admin_interval_minutes')))
        ->map(fn (int $minutes): string => sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60));
@endphp

<div class="admin-form-grid">
    <fieldset class="admin-customer-choice admin-form-wide">
        <legend>Customer</legend>
        <div class="admin-customer-choice-grid">
            <label class="admin-customer-choice-option">
                <input type="radio" name="customer_mode" value="existing" data-customer-mode required @checked($customerMode === 'existing')>
                <span><strong>Existing customer</strong><small>Search by name, email or telephone number</small></span>
            </label>
            <label class="admin-customer-choice-option">
                <input type="radio" name="customer_mode" value="new" data-customer-mode required @checked($customerMode === 'new')>
                <span><strong>New customer</strong><small>Create a customer while adding this appointment</small></span>
            </label>
        </div>
    </fieldset>

    <div class="admin-customer-panel admin-form-wide" data-customer-panel="existing" @if ($customerMode !== 'existing') hidden @endif>
        <label>
            Find customer
            <input
                type="search"
                name="customer_search"
                value="{{ old('customer_search', $currentCustomerLabel) }}"
                list="admin-customer-options"
                placeholder="Start typing a name, email or telephone number"
                autocomplete="off"
                aria-describedby="customer-search-help"
                data-customer-search
                data-customer-required
                @required($customerMode === 'existing')
                @disabled($customerMode !== 'existing')
            >
            <small id="customer-search-help">Start typing, then choose the matching customer from the suggestions.</small>
        </label>
        <input
            type="hidden"
            name="customer_id"
            value="{{ $currentCustomerId }}"
            data-customer-id
            @disabled($customerMode !== 'existing')
        >
        <datalist id="admin-customer-options">
            @foreach ($customers as $customer)
                @php($customerLabel = collect([$customer->name, $customer->email, $customer->phone])->filter()->join(' · '))
                <option value="{{ $customerLabel }}" data-customer-option-id="{{ $customer->id }}"></option>
            @endforeach
        </datalist>
    </div>

    <div class="admin-customer-panel admin-form-wide" data-customer-panel="new" @if ($customerMode !== 'new') hidden @endif>
        <div class="admin-customer-fields">
            <label>
                Customer name
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" maxlength="120" autocomplete="name" data-customer-required @required($customerMode === 'new') @disabled($customerMode !== 'new')>
            </label>
            <label>
                Email address <small>Optional</small>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}" maxlength="190" autocomplete="email" @disabled($customerMode !== 'new')>
            </label>
            <x-phone-input
                name="customer_phone"
                country-name="customer_phone_country"
                id="customer_phone"
                label="Telephone number"
                variant="admin"
                :required="$customerMode === 'new'"
                :disabled="$customerMode !== 'new'"
                :customer-required="true"
            />
        </div>
    </div>

    <fieldset class="admin-dropdown-field">
        <legend>Treatment</legend>
        <details class="admin-dropdown" data-admin-dropdown data-admin-dropdown-placeholder="Choose a treatment">
            <summary><span data-admin-dropdown-label>{{ $treatmentOptions->get($selectedTreatment, 'Choose a treatment') }}</span><span class="admin-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
            <div class="admin-dropdown__menu" role="group" aria-label="Treatment options">
                @foreach ($treatmentOptions as $value => $label)
                    <label class="admin-dropdown__option">
                        <input type="radio" name="treatment_slug" value="{{ $value }}" data-admin-dropdown-option data-admin-option-label="{{ $label }}" @checked($selectedTreatment === (string) $value)>
                        <span>{{ $label }}</span><b aria-hidden="true">✓</b>
                    </label>
                @endforeach
            </div>
        </details>
    </fieldset>
    <label>
        Date
        <input type="date" name="appointment_date" value="{{ $appointmentDate }}" required>
    </label>
    <fieldset class="admin-dropdown-field">
        <legend>Time</legend>
        <details class="admin-dropdown" data-admin-dropdown data-admin-dropdown-placeholder="Choose a time">
            <summary><span data-admin-dropdown-label>{{ $appointmentTimes->contains($appointmentTime) ? $appointmentTime : 'Choose a time' }}</span><span class="admin-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
            <div class="admin-dropdown__menu admin-dropdown__menu--scroll" role="group" aria-label="Appointment time options">
                @foreach ($appointmentTimes as $time)
                    <label class="admin-dropdown__option">
                        <input type="radio" name="appointment_time" value="{{ $time }}" data-admin-dropdown-option data-admin-option-label="{{ $time }}" required @checked($appointmentTime === $time)>
                        <span>{{ $time }}</span><b aria-hidden="true">✓</b>
                    </label>
                @endforeach
            </div>
        </details>
    </fieldset>
    <fieldset class="admin-dropdown-field">
        <legend>Duration</legend>
        <details class="admin-dropdown" data-admin-dropdown data-admin-dropdown-placeholder="Choose a duration">
            <summary><span data-admin-dropdown-label>{{ config('booking.duration_options')[$selectedDuration]['label'] ?? 'Choose a duration' }}</span><span class="admin-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
            <div class="admin-dropdown__menu" role="group" aria-label="Duration options">
                @foreach (config('booking.duration_options') as $minutes => $duration)
                    <label class="admin-dropdown__option">
                        <input type="radio" name="duration_minutes" value="{{ $minutes }}" data-admin-dropdown-option data-admin-option-label="{{ $duration['label'] }}" required @checked($selectedDuration === $minutes)>
                        <span>{{ $duration['label'] }}</span><b aria-hidden="true">✓</b>
                    </label>
                @endforeach
            </div>
        </details>
    </fieldset>
    <fieldset class="admin-dropdown-field">
        <legend>Status</legend>
        <details class="admin-dropdown" data-admin-dropdown data-admin-dropdown-placeholder="Choose a status">
            <summary><span data-admin-dropdown-label>{{ $statusOptions[$selectedStatus] ?? 'Choose a status' }}</span><span class="admin-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
            <div class="admin-dropdown__menu" role="group" aria-label="Status options">
                @foreach ($statusOptions as $value => $label)
                    <label class="admin-dropdown__option">
                        <input type="radio" name="status" value="{{ $value }}" data-admin-dropdown-option data-admin-option-label="{{ $label }}" required @checked($selectedStatus === $value)>
                        <span>{{ $label }}</span><b aria-hidden="true">✓</b>
                    </label>
                @endforeach
            </div>
        </details>
    </fieldset>
    <div
        class="admin-conflict-status admin-form-wide"
        role="status"
        aria-live="polite"
        data-appointment-conflict
        data-conflict-url="{{ route('admin.appointments.conflict') }}"
        @if ($editingAppointment) data-appointment-id="{{ $editingAppointment->id }}" @endif
        hidden
    ></div>
    <label class="admin-form-wide">
        Private appointment notes
        <textarea name="notes" rows="6" maxlength="5000" placeholder="Anything useful for this appointment">{{ old('notes', $editingAppointment->notes ?? '') }}</textarea>
    </label>
</div>
