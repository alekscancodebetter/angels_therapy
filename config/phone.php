<?php

return [
    'default_country' => 'GB',

    'countries' => [
        'GB' => ['name' => 'United Kingdom', 'flag' => '🇬🇧', 'dial_code' => '+44', 'national_digits' => [10], 'trunk_prefix' => '0', 'placeholder' => '7911 123456'],
        'IE' => ['name' => 'Ireland', 'flag' => '🇮🇪', 'dial_code' => '+353', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '85 123 4567'],
        'DE' => ['name' => 'Germany', 'flag' => '🇩🇪', 'dial_code' => '+49', 'national_digits' => range(5, 11), 'trunk_prefix' => '0', 'placeholder' => '1512 3456789'],
        'FR' => ['name' => 'France', 'flag' => '🇫🇷', 'dial_code' => '+33', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '6 12 34 56 78'],
        'ES' => ['name' => 'Spain', 'flag' => '🇪🇸', 'dial_code' => '+34', 'national_digits' => [9], 'trunk_prefix' => null, 'placeholder' => '612 345 678'],
        'IT' => ['name' => 'Italy', 'flag' => '🇮🇹', 'dial_code' => '+39', 'national_digits' => range(6, 11), 'trunk_prefix' => null, 'placeholder' => '312 345 6789'],
        'PT' => ['name' => 'Portugal', 'flag' => '🇵🇹', 'dial_code' => '+351', 'national_digits' => [9], 'trunk_prefix' => null, 'placeholder' => '912 345 678'],
        'NL' => ['name' => 'Netherlands', 'flag' => '🇳🇱', 'dial_code' => '+31', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '6 12345678'],
        'BE' => ['name' => 'Belgium', 'flag' => '🇧🇪', 'dial_code' => '+32', 'national_digits' => [8, 9], 'trunk_prefix' => '0', 'placeholder' => '470 12 34 56'],
        'CH' => ['name' => 'Switzerland', 'flag' => '🇨🇭', 'dial_code' => '+41', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '79 123 45 67'],
        'AT' => ['name' => 'Austria', 'flag' => '🇦🇹', 'dial_code' => '+43', 'national_digits' => range(4, 13), 'trunk_prefix' => '0', 'placeholder' => '664 1234567'],
        'BG' => ['name' => 'Bulgaria', 'flag' => '🇧🇬', 'dial_code' => '+359', 'national_digits' => [8, 9], 'trunk_prefix' => '0', 'placeholder' => '88 123 4567'],
        'RO' => ['name' => 'Romania', 'flag' => '🇷🇴', 'dial_code' => '+40', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '712 345 678'],
        'PL' => ['name' => 'Poland', 'flag' => '🇵🇱', 'dial_code' => '+48', 'national_digits' => [9], 'trunk_prefix' => null, 'placeholder' => '512 345 678'],
        'GR' => ['name' => 'Greece', 'flag' => '🇬🇷', 'dial_code' => '+30', 'national_digits' => [10], 'trunk_prefix' => null, 'placeholder' => '691 234 5678'],
        'CY' => ['name' => 'Cyprus', 'flag' => '🇨🇾', 'dial_code' => '+357', 'national_digits' => [8], 'trunk_prefix' => null, 'placeholder' => '96 123456'],
        'US' => ['name' => 'United States', 'flag' => '🇺🇸', 'dial_code' => '+1', 'national_digits' => [10], 'trunk_prefix' => null, 'placeholder' => '212 555 0123'],
        'CA' => ['name' => 'Canada', 'flag' => '🇨🇦', 'dial_code' => '+1', 'national_digits' => [10], 'trunk_prefix' => null, 'placeholder' => '416 555 0123'],
        'AU' => ['name' => 'Australia', 'flag' => '🇦🇺', 'dial_code' => '+61', 'national_digits' => [9], 'trunk_prefix' => '0', 'placeholder' => '412 345 678'],
        'NZ' => ['name' => 'New Zealand', 'flag' => '🇳🇿', 'dial_code' => '+64', 'national_digits' => range(8, 10), 'trunk_prefix' => '0', 'placeholder' => '21 123 4567'],
        'IN' => ['name' => 'India', 'flag' => '🇮🇳', 'dial_code' => '+91', 'national_digits' => [10], 'trunk_prefix' => '0', 'placeholder' => '98765 43210'],
    ],
];
