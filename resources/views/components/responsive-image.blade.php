@props([
    'src',
    'alt',
    'width' => 1536,
    'height' => 864,
    'sizes' => '100vw',
    'loading' => null,
    'fetchpriority' => null,
    'decoding' => null,
])

@php
    $imageBase = preg_replace('/\.[^.]+$/', '', (string) $src);
    $imageAttributes = array_filter([
        'loading' => $loading,
        'fetchpriority' => $fetchpriority,
        'decoding' => $decoding,
    ]);
@endphp

<picture>
    <source
        type="image/webp"
        srcset="{{ asset($imageBase.'-768.webp') }} 768w, {{ asset($imageBase.'-1280.webp') }} 1280w, {{ asset($imageBase.'-1536.webp') }} 1536w"
        sizes="{{ $sizes }}"
    >
    <img
        src="{{ asset($src) }}"
        alt="{{ $alt }}"
        width="{{ $width }}"
        height="{{ $height }}"
        {{ $attributes->merge($imageAttributes) }}
    >
</picture>
