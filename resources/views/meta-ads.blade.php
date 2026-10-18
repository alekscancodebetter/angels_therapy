@php
    $metaAdsPixelId = config('meta_ads.pixel_id');
@endphp

@if (is_string($metaAdsPixelId) && $metaAdsPixelId !== '')
    <script id="meta-ads-config">
        window.angelTouchMetaAds = {
            pixelId: @json($metaAdsPixelId),
            bookingCompleted: @json($bookingCompleted ?? false),
            bookingEventName: @json(config('meta_ads.booking_event_name')),
            bookingEventValue: @json(config('meta_ads.booking_event_value')),
            bookingEventCurrency: @json(config('meta_ads.booking_event_currency')),
            phoneEventName: @json(config('meta_ads.phone_event_name')),
        };
    </script>
@endif
