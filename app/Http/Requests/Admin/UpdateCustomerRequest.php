<?php

namespace App\Http\Requests\Admin;

use App\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'email' => ['nullable', 'email:rfc', 'max:190', Rule::unique('customers', 'email')->ignore($this->route('customer'))],
            'phone_country' => ['required', 'string', Rule::in(array_keys(PhoneNumber::countries()))],
            'phone' => ['required', 'string', 'max:40', PhoneNumber::validationRule($this->string('phone_country')->toString())],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'phone_country.required' => 'Please choose a country calling code.',
            'phone_country.in' => 'Please choose a valid country calling code.',
            'phone.required' => 'Please enter the customer’s telephone number.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->string('phone')->trim()->toString();

        $this->merge([
            'phone_country' => PhoneNumber::inputCountryCode($this->string('phone_country')->toString(), $phone),
            'phone' => $phone,
        ]);
    }
}
