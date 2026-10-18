<?php

namespace App;

use Closure;

final class PhoneNumber
{
    public static function inputCountryCode(?string $countryCode, ?string $phoneNumber = null): string
    {
        $countryCode = strtoupper(trim((string) $countryCode));

        return $countryCode !== '' ? $countryCode : self::countryCode(null, $phoneNumber);
    }

    public static function countryCode(?string $countryCode, ?string $phoneNumber = null): string
    {
        $countryCode = strtoupper(trim((string) $countryCode));

        if (array_key_exists($countryCode, self::countries())) {
            return $countryCode;
        }

        $internationalNumber = self::internationalDigits((string) $phoneNumber);

        if ($internationalNumber !== null) {
            $countries = self::countries();
            uasort($countries, fn (array $left, array $right): int => strlen($right['dial_code']) <=> strlen($left['dial_code']));

            foreach ($countries as $code => $country) {
                if (str_starts_with($internationalNumber, ltrim($country['dial_code'], '+'))) {
                    return $code;
                }
            }
        }

        return (string) config('phone.default_country', 'GB');
    }

    public static function nationalNumber(string $countryCode, string $phoneNumber): string
    {
        $country = self::countries()[$countryCode] ?? null;

        if ($country === null) {
            return trim($phoneNumber);
        }

        $trimmedNumber = trim($phoneNumber);
        $digits = preg_replace('/\D+/', '', $trimmedNumber) ?? '';
        $dialDigits = ltrim($country['dial_code'], '+');

        if (str_starts_with($trimmedNumber, '+') && str_starts_with($digits, $dialDigits)) {
            return substr($digits, strlen($dialDigits));
        }

        if (str_starts_with($digits, '00'.$dialDigits)) {
            return substr($digits, strlen($dialDigits) + 2);
        }

        $trunkPrefix = $country['trunk_prefix'];

        if ($trunkPrefix !== null && str_starts_with($digits, $trunkPrefix)) {
            return substr($digits, strlen($trunkPrefix));
        }

        return $digits;
    }

    public static function normalize(string $countryCode, string $phoneNumber): string
    {
        $country = self::countries()[$countryCode] ?? null;

        if ($country === null || trim($phoneNumber) === '') {
            return trim($phoneNumber);
        }

        return $country['dial_code'].' '.self::nationalNumber($countryCode, $phoneNumber);
    }

    public static function isValid(string $countryCode, string $phoneNumber): bool
    {
        $country = self::countries()[$countryCode] ?? null;

        if ($country === null || preg_match('/^[0-9+\s().-]+$/', trim($phoneNumber)) !== 1) {
            return false;
        }

        $nationalDigits = self::nationalNumber($countryCode, $phoneNumber);

        return in_array(strlen($nationalDigits), $country['national_digits'], true);
    }

    public static function validationRule(string $countryCode): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($countryCode): void {
            if (trim((string) $value) === '' || ! array_key_exists($countryCode, self::countries())) {
                return;
            }

            if (! self::isValid($countryCode, (string) $value)) {
                $fail(self::validationMessage($countryCode));
            }
        };
    }

    public static function validationMessage(string $countryCode): string
    {
        $country = self::countries()[$countryCode] ?? null;

        if ($country === null) {
            return 'Please choose a valid country code.';
        }

        $minimumDigits = min($country['national_digits']);
        $maximumDigits = max($country['national_digits']);
        $expectedDigits = $minimumDigits === $maximumDigits
            ? "{$minimumDigits} digits"
            : "between {$minimumDigits} and {$maximumDigits} digits";

        return "Please enter a valid telephone number with {$expectedDigits} after {$country['dial_code']}.";
    }

    /** @return array<string, array{name: string, flag: string, dial_code: string, national_digits: array<int, int>, trunk_prefix: string|null, placeholder: string}> */
    public static function countries(): array
    {
        return config('phone.countries', []);
    }

    private static function internationalDigits(string $phoneNumber): ?string
    {
        $trimmedNumber = trim($phoneNumber);

        if (str_starts_with($trimmedNumber, '+')) {
            return preg_replace('/\D+/', '', $trimmedNumber) ?: null;
        }

        $digits = preg_replace('/\D+/', '', $trimmedNumber) ?? '';

        return str_starts_with($digits, '00') ? substr($digits, 2) : null;
    }
}
