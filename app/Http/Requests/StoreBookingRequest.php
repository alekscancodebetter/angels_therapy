<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'starts_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'treatment_slug' => ['required', 'string', Rule::in(array_keys(config('treatments')))],
            'duration_minutes' => ['required', 'integer', Rule::in(array_keys(config('booking.duration_options')))],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
            'terms_accepted' => ['accepted'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'starts_at.required' => 'Please choose an appointment time.',
            'treatment_slug.required' => 'Please choose a treatment.',
            'duration_minutes.required' => 'Please choose a treatment duration.',
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your telephone number.',
            'terms_accepted.accepted' => 'Please accept the terms and conditions before booking.',
        ];
    }

    /** @return array<int, callable> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['starts_at', 'duration_minutes'])) {
                    return;
                }

                $startsAt = CarbonImmutable::createFromFormat(
                    'Y-m-d\TH:i',
                    $this->string('starts_at')->toString(),
                    config('app.timezone'),
                );

                if (! Appointment::isBookableStart($startsAt, $this->integer('duration_minutes'))) {
                    $validator->errors()->add('starts_at', 'Choose one of the available appointment times.');
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->string('name')->trim()->toString(),
            'email' => Str::lower($this->string('email')->trim()->toString()),
            'phone' => $this->string('phone')->trim()->toString(),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return route('home').'#booking';
    }
}
