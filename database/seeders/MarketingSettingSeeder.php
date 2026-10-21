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
            'meta_pixel_id' => config('meta_ads.pixel_id'),
            'meta_booking_event_name' => config('meta_ads.booking_event_name'),
            'meta_booking_event_value' => config('meta_ads.booking_event_value'),
            'meta_booking_event_currency' => config('meta_ads.booking_event_currency'),
            'meta_phone_event_name' => config('meta_ads.phone_event_name'),
            'meta_phone_event_value' => config('meta_ads.phone_event_value'),
            'meta_phone_event_currency' => config('meta_ads.phone_event_currency'),
        ]);
    }
}
