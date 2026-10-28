<?php

return [
    'duration_minutes' => 60,
    'duration_options' => [
        60 => [
            'label' => '60 minutes',
            'price' => 75,
        ],
        90 => [
            'label' => '90 minutes',
            'price' => 110,
        ],
    ],
    'package' => [
        'label' => '3 × 60-minute sessions',
        'price' => 210,
        'note' => 'Paid in advance',
    ],
    'interval_minutes' => 60,
    'lead_time_hours' => 12,
    'booking_window_days' => 365,
    'opening_time' => '08:00',
    'closing_time' => '19:00',
    'admin_interval_minutes' => 30,
    'working_days' => [1, 2, 3, 4, 5],
];
