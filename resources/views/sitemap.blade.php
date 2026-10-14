{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
    </url>
    @foreach ($treatments as $slug => $treatment)
        <url>
            <loc>{{ route('treatments.show', $slug) }}</loc>
        </url>
    @endforeach
</urlset>
