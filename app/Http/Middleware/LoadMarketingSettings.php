<?php

namespace App\Http\Middleware;

use App\Models\MarketingSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LoadMarketingSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Schema::hasTable('marketing_settings')) {
            $settings = MarketingSetting::query()->first();

            if ($settings !== null) {
                config([
                    'google_tracking.mode' => $settings->tracking_mode,
                    'google_tracking.tag_id' => $settings->google_tag_id,
                    'google_tracking.analytics_measurement_id' => $settings->google_analytics_measurement_id,
                    'google_tracking.tag_manager_container_id' => $settings->google_tag_manager_container_id,
                    'google_ads.tag_id' => $settings->google_ads_tag_id,
                    'google_ads.booking_conversion_label' => $settings->google_ads_booking_conversion_label,
                    'google_ads.booking_conversion_value' => $settings->google_ads_booking_conversion_value,
                    'google_ads.booking_conversion_currency' => $settings->google_ads_booking_conversion_currency,
                    'google_ads.phone_conversion_label' => $settings->google_ads_phone_conversion_label,
                    'google_ads.phone_conversion_value' => $settings->google_ads_phone_conversion_value,
                    'google_ads.phone_conversion_currency' => $settings->google_ads_phone_conversion_currency,
                    'meta_ads.pixel_id' => $settings->meta_pixel_id,
                    'meta_ads.booking_event_name' => $settings->meta_booking_event_name,
                    'meta_ads.booking_event_value' => $settings->meta_booking_event_value,
                    'meta_ads.booking_event_currency' => $settings->meta_booking_event_currency,
                    'meta_ads.phone_event_name' => $settings->meta_phone_event_name,
                    'meta_ads.phone_event_value' => $settings->meta_phone_event_value,
                    'meta_ads.phone_event_currency' => $settings->meta_phone_event_currency,
                ]);
            }
        }

        return $next($request);
    }
}
