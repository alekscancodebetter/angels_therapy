@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php
    $canonicalBaseUrl = rtrim((string) config('app.canonical_url'), '/');
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ $canonicalBaseUrl }}{{ route('home', [], false) }}</loc>
    </url>
    <url>
        <loc>{{ $canonicalBaseUrl }}{{ route('privacy-policy', [], false) }}</loc>
    </url>
    <url>
        <loc>{{ $canonicalBaseUrl }}{{ route('terms-and-conditions', [], false) }}</loc>
    </url>
    @foreach ($treatments as $slug => $treatment)
        <url>
            <loc>{{ $canonicalBaseUrl }}{{ route('treatments.show', ['slug' => $slug], false) }}</loc>
        </url>
    @endforeach
</urlset>
