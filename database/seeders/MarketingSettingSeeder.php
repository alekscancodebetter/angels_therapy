<?php

namespace Database\Seeders;

use App\Models\MarketingSetting;
use Illuminate\Database\Seeder;

class MarketingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MarketingSetting::query()->firstOrCreate([], [
            'tracking_mode' => config('google_tracking.mode'),
            'google_tag_id' => config('google_tracking.tag_id'),
            'google_analytics_measurement_id' => config('google_tracking.analytics_measurement_id'),
            'google_tag_manager_container_id' => config('google_tracking.tag_manager_container_id'),
            'google_ads_tag_id' => config('google_ads.tag_id'),
            'google_ads_booking_conversion_label' => config('google_ads.booking_conversion_label'),
            'google_ads_booking_conversion_value' => config('google_ads.booking_conversion_value'),
            'google_ads_booking_conversion_currency' => config('google_ads.booking_conversion_currency'),
            'google_ads_phone_conversion_label' => config('google_ads.phone_conversion_label'),
            'google_ads_phone_conversion_value' => config('google_ads.phone_conversion_value'),
            'google_ads_phone_conversion_currency' => config('google_ads.phone_conversion_currency'),
        ]);
    }
}
