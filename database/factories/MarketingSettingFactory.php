<?php

namespace Database\Factories;

use App\Models\MarketingSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MarketingSetting>
 */
class MarketingSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tracking_mode' => 'direct',
            'google_tag_id' => 'GT-'.fake()->regexify('[A-Z0-9]{10}'),
            'google_analytics_measurement_id' => 'G-'.fake()->regexify('[A-Z0-9]{10}'),
            'google_ads_tag_id' => 'AW-'.fake()->numerify('#########'),
            'google_ads_booking_conversion_currency' => 'GBP',
            'google_ads_phone_conversion_currency' => 'GBP',
        ];
    }
}
