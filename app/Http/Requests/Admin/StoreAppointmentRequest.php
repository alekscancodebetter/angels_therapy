<?php

namespace App\Http\Requests\Admin;

use App\PhoneNumber;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Throwable;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'customer_mode' => ['required', Rule::in(['existing', 'new'])],
            'customer_id' => ['exclude_unless:customer_mode,existing', 'required', 'integer', 'exists:customers,id'],
            'customer_name' => ['exclude_unless:customer_mode,new', 'required', 'string', 'max:120'],
            'customer_email' => ['exclude_unless:customer_mode,new', 'nullable', 'email:rfc', 'max:190', 'unique:customers,email'],
            'customer_phone_country' => ['exclude_unless:customer_mode,new', 'required', 'string', Rule::in(array_keys(PhoneNumber::countries()))],
            'customer_phone' => ['exclude_unless:customer_mode,new', 'required', 'string', 'max:40', PhoneNumber::validationRule($this->string('customer_phone_country')->toString())],
            'treatment_slug' => ['nullable', 'string', Rule::in(array_keys(config('treatments')))],
            'appointment_date' => ['nullable', 'date_format:Y-m-d'],
            'appointment_time' => ['nullable', 'date_format:H:i'],
            'starts_at' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', Rule::in(array_map('intval', array_keys(config('booking.duration_options'))))],
            'status' => ['required', Rule::in(['scheduled', 'completed', 'cancelled', 'no_show'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /** @return array<int, callable> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('starts_at')) {
                    return;
                }

                try {
                    $startsAt = CarbonImmutable::parse((string) $this->input('starts_at'), config('app.timezone'));
                } catch (Throwable) {
                    return;
                }

                [$openingHour, $openingMinute] = array_map('intval', explode(':', config('booking.opening_time')));
                [$closingHour, $closingMinute] = array_map('intval', explode(':', config('booking.closing_time')));
                $openingMinutes = ($openingHour * 60) + $openingMinute;
                $closingMinutes = ($closingHour * 60) + $closingMinute;
                $startMinutes = ($startsAt->hour * 60) + $startsAt->minute;
                $intervalMinutes = (int) config('booking.admin_interval_minutes');

                if ($startsAt->second !== 0
                    || $startMinutes < $openingMinutes
                    || $startMinutes > $closingMinutes
                    || ($startMinutes - $openingMinutes) % $intervalMinutes !== 0) {
                    $validator->errors()->add('starts_at', 'Please choose a time from the list.');
                }
            },
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'customer_mode.required' => 'Please choose how to add the customer.',
            'customer_mode.in' => 'Please choose a valid customer option.',
            'customer_id.required' => 'Please choose an existing customer.',
            'customer_id.exists' => 'The selected customer could not be found.',
            'customer_name.required' => 'Please enter the new customer’s name.',
            'customer_email.email' => 'Please enter a valid email address for the new customer.',
            'customer_email.unique' => 'A customer with this email address already exists. Choose that customer from the search instead.',
            'customer_phone_country.required' => 'Please choose a country calling code for the new customer.',
            'customer_phone_country.in' => 'Please choose a valid country calling code for the new customer.',
            'customer_phone.required' => 'Please enter the new customer’s telephone number.',
            'appointment_date.date_format' => 'Please enter a valid appointment date.',
            'appointment_time.date_format' => 'Please choose a valid appointment time.',
            'starts_at.required' => 'Please choose a date and time.',
            'starts_at.date' => 'Please enter a valid date and time.',
            'duration_minutes.required' => 'Please choose a duration.',
            'duration_minutes.in' => 'Please choose either 60 or 90 minutes.',
            'status.required' => 'Please choose a status.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $customerMode = $this->string('customer_mode')->trim()->toString();
        $customerEmail = Str::lower($this->string('customer_email')->trim()->toString());
        $customerPhone = $this->string('customer_phone')->trim()->toString();
        $appointmentDate = $this->string('appointment_date')->trim()->toString();
        $appointmentTime = $this->string('appointment_time')->trim()->toString();

        $this->merge([
            'customer_mode' => $customerMode !== '' ? $customerMode : 'existing',
            'customer_name' => $this->string('customer_name')->trim()->toString(),
            'customer_email' => $customerEmail !== '' ? $customerEmail : null,
            'customer_phone_country' => PhoneNumber::inputCountryCode($this->string('customer_phone_country')->toString(), $customerPhone),
            'customer_phone' => $customerPhone !== '' ? $customerPhone : null,
            'starts_at' => $appointmentDate !== '' && $appointmentTime !== ''
                ? $appointmentDate.' '.$appointmentTime.':00'
                : $this->input('starts_at'),
        ]);
    }
}
