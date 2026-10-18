@props([
    'name' => 'phone',
    'countryName' => 'phone_country',
    'id' => 'phone',
    'label' => 'Telephone',
    'value' => '',
    'country' => null,
    'variant' => 'booking',
    'required' => true,
    'disabled' => false,
    'customerRequired' => false,
])

@php
    $countries = \App\PhoneNumber::countries();
    $submittedNumber = (string) old($name, $value);
    $submittedCountry = old($countryName, $country);
    $selectedCountryCode = \App\PhoneNumber::countryCode(is_string($submittedCountry) ? $submittedCountry : null, $submittedNumber);
    $selectedCountry = $countries[$selectedCountryCode];
    $displayNumber = \App\PhoneNumber::nationalNumber($selectedCountryCode, $submittedNumber);
    $isBooking = $variant === 'booking';
    $fieldClass = $isBooking ? 'booking-phone-field' : 'admin-phone-field';
    $controlClass = $isBooking ? 'booking-phone-control' : 'admin-phone-control';
    $dropdownClass = ($isBooking ? 'booking-dropdown' : 'admin-dropdown').' phone-country-dropdown';
    $errorId = $id.'_error';
    $countryErrorId = $id.'_country_error';
    $helpId = $id.'_help';
@endphp

<fieldset class="{{ $fieldClass }}" data-phone-field>
    <legend>{{ $label }}</legend>
    <div class="{{ $controlClass }}">
        <details
            class="{{ $dropdownClass }}"
            data-phone-country-dropdown
            @if ($isBooking)
                data-booking-dropdown
                data-booking-placeholder="Choose country"
            @else
                data-admin-dropdown
                data-admin-dropdown-placeholder="Choose country"
            @endif
            @if ($errors->has($countryName)) aria-invalid="true" open @endif
        >
            <summary aria-label="Country calling code">
                <span @if ($isBooking) data-booking-dropdown-label @else data-admin-dropdown-label @endif>{{ $selectedCountry['flag'] }} {{ $selectedCountry['dial_code'] }}</span>
                <span class="{{ $isBooking ? 'booking-dropdown__icon' : 'admin-dropdown__icon' }}" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span>
            </summary>
            <div class="{{ $isBooking ? 'booking-dropdown__menu booking-dropdown__menu--scroll' : 'admin-dropdown__menu admin-dropdown__menu--scroll' }}" role="group" aria-label="Country calling codes">
                @foreach ($countries as $countryCode => $phoneCountry)
                    @php
                        $minimumDigits = min($phoneCountry['national_digits']);
                        $maximumDigits = max($phoneCountry['national_digits']);
                    @endphp
                    <label class="{{ $isBooking ? 'booking-dropdown__option' : 'admin-dropdown__option' }}">
                        <input
                            type="radio"
                            name="{{ $countryName }}"
                            value="{{ $countryCode }}"
                            data-phone-country-option
                            data-phone-country-label="{{ $phoneCountry['name'] }}"
                            data-phone-dial-code="{{ $phoneCountry['dial_code'] }}"
                            data-phone-min-digits="{{ $minimumDigits }}"
                            data-phone-max-digits="{{ $maximumDigits }}"
                            data-phone-trunk-prefix="{{ $phoneCountry['trunk_prefix'] }}"
                            data-phone-placeholder="{{ $phoneCountry['placeholder'] }}"
                            data-phone-message="{{ \App\PhoneNumber::validationMessage($countryCode) }}"
                            @if ($isBooking)
                                data-booking-dropdown-option
                                data-booking-option-label="{{ $phoneCountry['flag'] }} {{ $phoneCountry['dial_code'] }}"
                            @else
                                data-admin-dropdown-option
                                data-admin-option-label="{{ $phoneCountry['flag'] }} {{ $phoneCountry['dial_code'] }}"
                            @endif
                            @checked($selectedCountryCode === $countryCode)
                            @required($required)
                            @disabled($disabled)
                            @if ($customerRequired) data-customer-required @endif
                        >
                        <span><span aria-hidden="true">{{ $phoneCountry['flag'] }}</span> {{ $phoneCountry['name'] }} <small>{{ $phoneCountry['dial_code'] }}</small></span><b aria-hidden="true">✓</b>
                    </label>
                @endforeach
            </div>
        </details>

        <label class="visually-hidden" for="{{ $id }}">{{ $label }} number after the country code</label>
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="tel"
            inputmode="tel"
            value="{{ $displayNumber }}"
            placeholder="{{ $selectedCountry['placeholder'] }}"
            maxlength="30"
            autocomplete="tel-national"
            data-phone-number
            aria-describedby="{{ $helpId }} @error($name) {{ $errorId }} @enderror @error($countryName) {{ $countryErrorId }} @enderror"
            @required($required)
            @disabled($disabled)
            @if ($customerRequired) data-customer-required @endif
            @error($name) aria-invalid="true" @enderror
        >
    </div>
    <small id="{{ $helpId }}" data-phone-help>{{ $selectedCountry['name'] }} {{ $selectedCountry['dial_code'] }}: {{ \App\PhoneNumber::validationMessage($selectedCountryCode) }}</small>
    @error($name)<small id="{{ $errorId }}">{{ $message }}</small>@enderror
    @error($countryName)<small id="{{ $countryErrorId }}">{{ $message }}</small>@enderror
</fieldset>
