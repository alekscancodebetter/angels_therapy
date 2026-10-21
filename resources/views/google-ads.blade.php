@php
    $trackingMode = config('google_tracking.mode');
    $googleTagId = config('google_tracking.tag_id');
    $analyticsMeasurementId = config('google_tracking.analytics_measurement_id');
    $tagManagerContainerId = config('google_tracking.tag_manager_container_id');
    $googleAdsTagId = config('google_ads.tag_id');
    $hasDirectGoogleTag = collect([$googleTagId, $analyticsMeasurementId, $googleAdsTagId])
        ->contains(fn ($value) => is_string($value) && $value !== '');
    $hasTagManager = is_string($tagManagerContainerId) && $tagManagerContainerId !== '';
@endphp

@if (($trackingMode === 'direct' && $hasDirectGoogleTag) || ($trackingMode === 'tag_manager' && $hasTagManager))
    <script id="google-ads-config">
        window.angelTouchGoogle = {
            mode: @json($trackingMode),
            tagId: @json($googleTagId),
            analyticsMeasurementId: @json($analyticsMeasurementId),
            tagManagerContainerId: @json($tagManagerContainerId),
            adsTagId: @json($googleAdsTagId),
            bookingCompleted: @json($bookingCompleted ?? false),
            bookingConversionLabel: @json(config('google_ads.booking_conversion_label')),
            bookingConversionValue: @json(config('google_ads.booking_conversion_value')),
            bookingConversionCurrency: @json(config('google_ads.booking_conversion_currency')),
            phoneConversionLabel: @json(config('google_ads.phone_conversion_label')),
            phoneConversionValue: @json(config('google_ads.phone_conversion_value')),
            phoneConversionCurrency: @json(config('google_ads.phone_conversion_currency')),
        };
    </script>
@endif
