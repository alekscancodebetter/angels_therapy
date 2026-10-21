@extends('admin.layout')

@section('title', 'Settings')

@section('content')
    @php
        $trackingMode = old('tracking_mode', $marketingSettings->tracking_mode ?: 'direct');
        $directConfigured = filled(old('google_tag_id', $marketingSettings->google_tag_id))
            || filled(old('google_analytics_measurement_id', $marketingSettings->google_analytics_measurement_id))
            || filled(old('google_ads_tag_id', $marketingSettings->google_ads_tag_id));
        $tagManagerConfigured = filled(old('google_tag_manager_container_id', $marketingSettings->google_tag_manager_container_id));
        $metaConfigured = filled(old('meta_pixel_id', $marketingSettings->meta_pixel_id));
    @endphp

    <div class="admin-page-heading">
        <div><p class="admin-eyebrow">ACCOUNT &amp; INTEGRATIONS</p><h1>Admin settings</h1><p>Update sign-in details and manage consent-gated Google and Meta measurement.</p></div>
    </div>

    @include('admin.partials.errors')

    <form class="admin-form admin-settings-form" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PATCH')

        <section class="admin-panel admin-form-panel">
            <div class="admin-panel-heading">
                <div><p class="admin-eyebrow">ACCOUNT</p><h2>Sign-in details</h2></div>
            </div>
            <div class="admin-form-grid">
                <label>Name<input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="120" required></label>
                <label>Email address<input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="190" required></label>
                <label>New password <small>Leave empty to keep the current password</small><input type="password" name="password" autocomplete="new-password"></label>
                <label>Confirm new password<input type="password" name="password_confirmation" autocomplete="new-password"></label>
            </div>
        </section>

        <section class="admin-panel admin-form-panel">
            <div class="admin-panel-heading admin-tracking-heading">
                <div><p class="admin-eyebrow">GOOGLE</p><h2>Tracking and advertising</h2></div>
                <span class="admin-integration-status {{ ($trackingMode === 'direct' ? $directConfigured : $tagManagerConfigured) ? 'is-configured' : '' }}">
                    {{ ($trackingMode === 'direct' ? $directConfigured : $tagManagerConfigured) ? 'Configured' : 'Needs IDs' }}
                </span>
            </div>
            <p class="admin-settings-help">Choose one installation method to avoid duplicate page views and conversions. Tags load only after a visitor selects “Accept all”.</p>

            <div class="admin-form-grid">
                <label class="admin-form-wide">Installation method
                    <select name="tracking_mode" required>
                        <option value="direct" @selected($trackingMode === 'direct')>Direct Google tag (recommended)</option>
                        <option value="tag_manager" @selected($trackingMode === 'tag_manager')>Google Tag Manager</option>
                    </select>
                    <small>Direct mode uses the IDs below. Tag Manager mode loads only the GTM container and sends booking_completed and phone_click data-layer events.</small>
                </label>

                <div class="admin-settings-divider admin-form-wide"><strong>Direct Google tag</strong><span>Used only when “Direct Google tag” is selected.</span></div>
                <label>Google tag ID <small>Optional shared tag, beginning GT-</small><input type="text" name="google_tag_id" value="{{ old('google_tag_id', $marketingSettings->google_tag_id) }}" maxlength="40" placeholder="GT-XXXXXXXXXX" spellcheck="false"></label>
                <label>GA4 Measurement ID <small>From the GA4 web data stream, beginning G-</small><input type="text" name="google_analytics_measurement_id" value="{{ old('google_analytics_measurement_id', $marketingSettings->google_analytics_measurement_id) }}" maxlength="40" placeholder="G-XXXXXXXXXX" spellcheck="false"></label>
                <label>Google Ads tag ID <small>Conversion ID beginning AW-</small><input type="text" name="google_ads_tag_id" value="{{ old('google_ads_tag_id', $marketingSettings->google_ads_tag_id) }}" maxlength="40" placeholder="AW-123456789" spellcheck="false"></label>

                <div class="admin-settings-divider admin-form-wide"><strong>Google Tag Manager</strong><span>Used only when “Google Tag Manager” is selected.</span></div>
                <label class="admin-form-wide">GTM container ID <small>From the container workspace, beginning GTM-</small><input type="text" name="google_tag_manager_container_id" value="{{ old('google_tag_manager_container_id', $marketingSettings->google_tag_manager_container_id) }}" maxlength="40" placeholder="GTM-XXXXXXX" spellcheck="false"></label>

                <div class="admin-settings-divider admin-form-wide"><strong>Google Ads conversions</strong><span>Labels come from the individual Ads conversion actions.</span></div>
                <label>Booking conversion label<input type="text" name="google_ads_booking_conversion_label" value="{{ old('google_ads_booking_conversion_label', $marketingSettings->google_ads_booking_conversion_label) }}" maxlength="100" placeholder="AbCdEFghijkLMnoP"></label>
                <label>Booking value<input type="number" name="google_ads_booking_conversion_value" value="{{ old('google_ads_booking_conversion_value', $marketingSettings->google_ads_booking_conversion_value) }}" min="0" max="99999999" step="0.01" inputmode="decimal" placeholder="0.00"></label>
                <label>Booking currency<input type="text" name="google_ads_booking_conversion_currency" value="{{ old('google_ads_booking_conversion_currency', $marketingSettings->google_ads_booking_conversion_currency ?: 'GBP') }}" minlength="3" maxlength="3" placeholder="GBP" required></label>
                <label>Phone-click conversion label<input type="text" name="google_ads_phone_conversion_label" value="{{ old('google_ads_phone_conversion_label', $marketingSettings->google_ads_phone_conversion_label) }}" maxlength="100" placeholder="QrStUVwxyz12345"></label>
                <label>Phone-click value<input type="number" name="google_ads_phone_conversion_value" value="{{ old('google_ads_phone_conversion_value', $marketingSettings->google_ads_phone_conversion_value) }}" min="0" max="99999999" step="0.01" inputmode="decimal" placeholder="1.00"></label>
                <label>Phone-click currency<input type="text" name="google_ads_phone_conversion_currency" value="{{ old('google_ads_phone_conversion_currency', $marketingSettings->google_ads_phone_conversion_currency ?: 'GBP') }}" minlength="3" maxlength="3" placeholder="GBP" required></label>
            </div>
        </section>

        <section class="admin-panel admin-form-panel">
            <div class="admin-panel-heading admin-tracking-heading">
                <div><p class="admin-eyebrow">META</p><h2>Meta Pixel</h2></div>
                <span class="admin-integration-status {{ $metaConfigured ? 'is-configured' : '' }}">
                    {{ $metaConfigured ? 'Configured' : 'Needs Pixel ID' }}
                </span>
            </div>
            <p class="admin-settings-help">The Pixel loads only after “Accept all”. It records PageView automatically, plus the selected booking and phone-click events.</p>

            <div class="admin-form-grid">
                <label class="admin-form-wide">Meta Pixel ID <small>Find this numeric ID in Meta Events Manager → Data sources.</small><input type="text" name="meta_pixel_id" value="{{ old('meta_pixel_id', $marketingSettings->meta_pixel_id) }}" maxlength="30" inputmode="numeric" pattern="[0-9]+" placeholder="123456789012345" spellcheck="false"></label>

                <div class="admin-settings-divider admin-form-wide"><strong>Completed booking</strong><span>Sent once on the booking confirmation page.</span></div>
                <label>Meta event
                    <select name="meta_booking_event_name" required>
                        @foreach (['Schedule', 'Lead', 'Contact', 'CompleteRegistration'] as $eventName)
                            <option value="{{ $eventName }}" @selected(old('meta_booking_event_name', $marketingSettings->meta_booking_event_name ?: 'Schedule') === $eventName)>{{ $eventName }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Booking value<input type="number" name="meta_booking_event_value" value="{{ old('meta_booking_event_value', $marketingSettings->meta_booking_event_value) }}" min="0" max="99999999" step="0.01" inputmode="decimal" placeholder="0.00"></label>
                <label>Booking currency<input type="text" name="meta_booking_event_currency" value="{{ old('meta_booking_event_currency', $marketingSettings->meta_booking_event_currency ?: 'GBP') }}" minlength="3" maxlength="3" placeholder="GBP" required></label>

                <div class="admin-settings-divider admin-form-wide"><strong>Phone click</strong><span>Sent when a visitor selects a telephone link.</span></div>
                <label>Meta event
                    <select name="meta_phone_event_name" required>
                        @foreach (['Contact', 'Lead'] as $eventName)
                            <option value="{{ $eventName }}" @selected(old('meta_phone_event_name', $marketingSettings->meta_phone_event_name ?: 'Contact') === $eventName)>{{ $eventName }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Phone-click value<input type="number" name="meta_phone_event_value" value="{{ old('meta_phone_event_value', $marketingSettings->meta_phone_event_value) }}" min="0" max="99999999" step="0.01" inputmode="decimal" placeholder="0.00"></label>
                <label>Phone-click currency<input type="text" name="meta_phone_event_currency" value="{{ old('meta_phone_event_currency', $marketingSettings->meta_phone_event_currency ?: 'GBP') }}" minlength="3" maxlength="3" placeholder="GBP" required></label>
            </div>
        </section>

        <div class="admin-form-actions admin-settings-actions"><span>Changes apply to the public website immediately.</span><button class="admin-button admin-button--primary" type="submit">Save settings</button></div>
    </form>
@endsection
