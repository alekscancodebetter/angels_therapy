@php
    $googleAdsTagId = config('google_ads.tag_id');
@endphp

@if (is_string($googleAdsTagId) && $googleAdsTagId !== '')
    <script id="google-ads-config">
        window.angelTouchGoogleAds = {
            tagId: @json($googleAdsTagId),
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
