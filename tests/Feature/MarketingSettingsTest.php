<?php

namespace Tests\Feature;

use App\Models\MarketingSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_manage_google_tracking_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Tracking and advertising')
            ->assertSee('GA4 Measurement ID')
            ->assertSee('GTM container ID');

        $this->actingAs($admin)
            ->patch(route('admin.settings.update'), [
                'name' => 'Tracking Admin',
                'email' => 'tracking@example.com',
                'tracking_mode' => 'direct',
                'google_tag_id' => 'gt-abc123def4',
                'google_analytics_measurement_id' => 'g-abc123def4',
                'google_tag_manager_container_id' => '',
                'google_ads_tag_id' => 'aw-123456789',
                'google_ads_booking_conversion_label' => 'BookingLabel123',
                'google_ads_booking_conversion_value' => '75.00',
                'google_ads_booking_conversion_currency' => 'gbp',
                'google_ads_phone_conversion_label' => 'PhoneLabel123',
                'google_ads_phone_conversion_value' => '1.00',
                'google_ads_phone_conversion_currency' => 'gbp',
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Admin and Google tracking settings updated.');

        $this->assertDatabaseHas('marketing_settings', [
            'id' => 1,
            'tracking_mode' => 'direct',
            'google_tag_id' => 'GT-ABC123DEF4',
            'google_analytics_measurement_id' => 'G-ABC123DEF4',
            'google_ads_tag_id' => 'AW-123456789',
            'google_ads_booking_conversion_label' => 'BookingLabel123',
            'google_ads_booking_conversion_currency' => 'GBP',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('GT-ABC123DEF4', false)
            ->assertSee('G-ABC123DEF4', false)
            ->assertSee('AW-123456789', false);
    }

    public function test_tag_manager_mode_requires_a_valid_container_id(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('admin.settings.edit'))
            ->patch(route('admin.settings.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'tracking_mode' => 'tag_manager',
                'google_tag_manager_container_id' => 'not-a-container',
                'google_ads_booking_conversion_currency' => 'GBP',
                'google_ads_phone_conversion_currency' => 'GBP',
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHasErrors('google_tag_manager_container_id');

        $this->assertDatabaseEmpty('marketing_settings');
    }

    public function test_tag_manager_configuration_is_used_without_loading_direct_google_ids(): void
    {
        MarketingSetting::factory()->create([
            'tracking_mode' => 'tag_manager',
            'google_tag_manager_container_id' => 'GTM-ABC1234',
            'google_tag_id' => 'GT-DIRECT1234',
            'google_analytics_measurement_id' => 'G-DIRECT1234',
            'google_ads_tag_id' => 'AW-123456789',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('mode: "tag_manager"', false)
            ->assertSee('GTM-ABC1234', false);
    }
}
