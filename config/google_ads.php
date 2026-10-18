<?php

return [
    'tag_id' => env('GOOGLE_ADS_TAG_ID'),
    'booking_conversion_label' => env('GOOGLE_ADS_BOOKING_CONVERSION_LABEL'),
    'booking_conversion_value' => env('GOOGLE_ADS_BOOKING_CONVERSION_VALUE'),
    'booking_conversion_currency' => env('GOOGLE_ADS_BOOKING_CONVERSION_CURRENCY', 'GBP'),
    'phone_conversion_label' => env('GOOGLE_ADS_PHONE_CONVERSION_LABEL'),
    'phone_conversion_value' => env('GOOGLE_ADS_PHONE_CONVERSION_VALUE', 1),
    'phone_conversion_currency' => env('GOOGLE_ADS_PHONE_CONVERSION_CURRENCY', 'GBP'),
];
