<?php

namespace App\Models;

use Database\Factories\MarketingSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'tracking_mode',
    'google_tag_id',
    'google_analytics_measurement_id',
    'google_tag_manager_container_id',
    'google_ads_tag_id',
    'google_ads_booking_conversion_label',
    'google_ads_booking_conversion_value',
    'google_ads_booking_conversion_currency',
    'google_ads_phone_conversion_label',
    'google_ads_phone_conversion_value',
    'google_ads_phone_conversion_currency',
])]
class MarketingSetting extends Model
{
    /** @use HasFactory<MarketingSettingFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'google_ads_booking_conversion_value' => 'decimal:2',
            'google_ads_phone_conversion_value' => 'decimal:2',
        ];
    }
}
