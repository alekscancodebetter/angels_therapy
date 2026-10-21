<?php

return [
    'pixel_id' => env('META_PIXEL_ID'),
    'booking_event_name' => env('META_ADS_BOOKING_EVENT_NAME', 'Schedule'),
    'booking_event_value' => env('META_ADS_BOOKING_VALUE'),
    'booking_event_currency' => env('META_ADS_BOOKING_CURRENCY', 'GBP'),
    'phone_event_name' => env('META_ADS_PHONE_EVENT_NAME', 'Contact'),
    'phone_event_value' => env('META_ADS_PHONE_VALUE'),
    'phone_event_currency' => env('META_ADS_PHONE_CURRENCY', 'GBP'),
];
