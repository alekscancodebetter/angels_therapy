<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190', Rule::unique('users', 'email')->ignore($this->user())],
            'password' => ['nullable', 'confirmed', Password::min(12)->letters()->numbers()],
            'tracking_mode' => ['required', Rule::in(['direct', 'tag_manager'])],
            'google_tag_id' => ['nullable', 'string', 'max:40', 'regex:/^GT-[A-Z0-9]+$/i'],
            'google_analytics_measurement_id' => ['nullable', 'string', 'max:40', 'regex:/^G-[A-Z0-9]+$/i'],
            'google_tag_manager_container_id' => ['nullable', 'string', 'max:40', 'regex:/^GTM-[A-Z0-9]+$/i', 'required_if:tracking_mode,tag_manager'],
            'google_ads_tag_id' => ['nullable', 'string', 'max:40', 'regex:/^AW-[0-9]+$/i'],
            'google_ads_booking_conversion_label' => ['nullable', 'string', 'max:100'],
            'google_ads_booking_conversion_value' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'google_ads_booking_conversion_currency' => ['required', 'alpha', 'size:3'],
            'google_ads_phone_conversion_label' => ['nullable', 'string', 'max:100'],
            'google_ads_phone_conversion_value' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'google_ads_phone_conversion_currency' => ['required', 'alpha', 'size:3'],
        ];
    }
}
