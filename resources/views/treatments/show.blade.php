<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $canonicalUrl = rtrim((string) config('app.canonical_url'), '/').route('treatments.show', ['slug' => $slug], false);
        $treatmentImageBase = preg_replace('/\.[^.]+$/', '', $treatment['image']);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="{{ $treatment['title'] }} in Harrogate | Angel Touch Therapy">
    <meta name="description" content="{{ $treatment['summary'] }} Angel Touch Therapy in Harrogate with qualified and fully insured massage therapist Villy.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Angel Touch Therapy">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="{{ $treatment['title'] }} in Harrogate | Angel Touch Therapy">
    <meta property="og:description" content="{{ $treatment['summary'] }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ asset($treatment['image']) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $treatment['title'] }} in Harrogate | Angel Touch Therapy">
    <meta name="twitter:description" content="{{ $treatment['summary'] }}">
    <meta name="twitter:image" content="{{ asset($treatment['image']) }}">
    <title>{{ $treatment['title'] }} in Harrogate | Angel Touch Therapy</title>
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('favicons')
    @include('google-ads', ['bookingCompleted' => false])
    @include('meta-ads', ['bookingCompleted' => false])
    <link
        rel="preload"
        href="{{ asset($treatmentImageBase.'-1280.webp') }}"
        as="image"
        type="image/webp"
        imagesrcset="{{ asset($treatmentImageBase.'-768.webp') }} 768w, {{ asset($treatmentImageBase.'-1280.webp') }} 1280w, {{ asset($treatmentImageBase.'-1536.webp') }} 1536w"
        imagesizes="100vw"
        fetchpriority="high"
    >
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $treatment['title'],
            'description' => $treatment['summary'],
            'url' => $canonicalUrl,
            'image' => asset($treatment['image']),
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Harrogate',
            ],
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => 'Angel Touch Therapy',
                'telephone' => '+447915910033',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'NuDerma Clinic, 137 Wetherby Road',
                    'addressLocality' => 'Harrogate',
                    'postalCode' => 'HG2 7AA',
                    'addressCountry' => 'GB',
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
</head>
<body class="treatment-page">
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header site-header--solid" data-header>
        <a class="brand" href="{{ route('home') }}" aria-label="Angel Touch Therapy home">
            <img class="brand__mark" src="{{ asset('images/angel-touch-customer-mark.webp') }}" alt="Angel Touch Therapy wing and golden hand logo" width="720" height="720" fetchpriority="high">
            <span class="brand__copy" aria-label="Angel Touch Therapy Harrogate">
                <strong>Angel <b>Touch</b></strong>
                <small><span>Therapy</span><i aria-hidden="true"></i><span>Harrogate</span></small>
            </span>
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Main navigation">
            <details class="nav-dropdown">
                <summary>Treatments <span aria-hidden="true">⌄</span></summary>
                <div class="nav-dropdown__menu">
                    <a href="{{ route('home') }}#treatments"><small aria-hidden="true">→</small><span>All treatments</span></a>
                    @foreach (config('treatments') as $treatmentSlug => $navTreatment)
                        <a href="{{ route('treatments.show', $treatmentSlug) }}"><small aria-hidden="true">→</small><span>{{ $navTreatment['title'] }}</span></a>
                    @endforeach
                </div>
            </details>
            <a href="{{ route('home') }}#about">About Villy</a>
            <a href="{{ route('home') }}#stories">Reviews</a>
            <a href="{{ route('home') }}#contact">Contact</a>
            <a class="nav-booking-link" href="{{ route('home') }}#booking">
                <span><strong>Book now</strong><small>Choose your appointment</small></span>
                <span aria-hidden="true">→</span>
            </a>
        </nav>

        <div class="header-actions">
            <a class="phone-link" href="tel:+447915910033" aria-label="Call Angel Touch Therapy on 07915 910033"><span>Call now</span><strong>07915 910 033</strong></a>
            <a class="button button--light" href="{{ route('home') }}#booking">Book appointment</a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" data-menu-toggle>
                <span></span><span></span><span></span><b data-menu-label>Open menu</b>
            </button>
        </div>
    </header>

    <main id="main">
        <section class="treatment-hero" aria-labelledby="treatment-title">
            <x-responsive-image
                class="treatment-hero__image"
                src="{{ $treatment['image'] }}"
                alt="{{ $treatment['title'] }} treatment at Angel Touch Therapy in Harrogate"
                sizes="100vw"
                fetchpriority="high"
            />
            <div class="treatment-hero__shade"></div>
            <div class="container treatment-hero__content">
                <a class="back-link" href="{{ route('home') }}#treatments">← All treatments</a>
                <p class="eyebrow">{{ $treatment['number'] }} · {{ $treatment['kicker'] }}</p>
                <h1 id="treatment-title">{{ $treatment['title'] }}</h1>
                <p>{{ $treatment['summary'] }}</p>
            </div>
        </section>

        <section class="treatment-overview section section--ivory" aria-labelledby="overview-title">
            <div class="container treatment-overview__grid reveal">
                <div>
                    <p class="eyebrow eyebrow--dark">About the treatment</p>
                    <h2 id="overview-title">A treatment shaped around your body.</h2>
                </div>
                <div class="treatment-overview__copy">
                    <p>{{ $treatment['intro'] }}</p>
                    <p>{{ $treatment['detail'] }}</p>
                </div>
                <aside class="treatment-price-list" aria-label="Price and duration">
                    <p>Prices for every massage</p>
                    @foreach (config('booking.duration_options') as $option)
                        <div><span>{{ $option['label'] }}</span><strong>£{{ $option['price'] }}</strong></div>
                    @endforeach
                    <div><span>{{ config('booking.package.label') }}</span><strong>£{{ config('booking.package.price') }}</strong></div>
                    <small>{{ config('booking.package.note') }}. Package sessions are arranged individually.</small>
                </aside>
            </div>
        </section>

        @include('google-reviews', [
            'sectionId' => 'treatment-reviews',
            'heading' => 'What clients say on Google.',
            'compact' => true,
            'reviewGroup' => $slug,
        ])

        <section class="treatment-benefits section" aria-labelledby="suited-title">
            <div class="container">
                <div class="section-heading reveal">
                    <div>
                        <p class="eyebrow eyebrow--gold">Your treatment</p>
                        <h2 id="suited-title">When this massage<br>may feel right.</h2>
                    </div>
                    <p>Massage is always personal. These examples are a guide; Villy will discuss your needs before the treatment begins.</p>
                </div>

                <div class="treatment-benefits__grid reveal">
                    <div class="treatment-list-panel">
                        <h3>This treatment can be suited to</h3>
                        <ul>
                            @foreach ($treatment['suited_to'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="treatment-list-panel treatment-list-panel--sand">
                        <h3>Areas Villy can focus on</h3>
                        <ul>
                            @foreach ($treatment['focus_areas'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="treatment-process section section--forest" aria-labelledby="process-title">
            <div class="container">
                <div class="treatment-process__heading reveal">
                    <p class="eyebrow">What to expect</p>
                    <h2 id="process-title">Professional, personal<br>and confidential.</h2>
                </div>
                <div class="process-grid">
                    @foreach ($treatment['approach'] as $index => [$title, $copy])
                        <article class="process-card reveal">
                            <span>0{{ $index + 1 }}</span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $copy }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="treatment-cta section section--sand" aria-labelledby="book-treatment-title">
            <div class="container treatment-cta__grid reveal">
                <div>
                    <p class="eyebrow eyebrow--dark">Book with Villy</p>
                    <h2 id="book-treatment-title">Ready to discuss your treatment?</h2>
                </div>
                <div>
                    <p>Every appointment begins with a full consultation. Contact Villy to ask a question or request a {{ strtolower($treatment['title']) }} appointment.</p>
                    <div class="treatment-cta__actions">
                        <a class="button button--dark" href="{{ route('home') }}#booking">Book your treatment</a>
                        <a class="text-link" href="tel:+447915910033">Call 07915 910 033</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="related-treatments section" aria-labelledby="related-title">
            <div class="container">
                <div class="related-treatments__heading reveal">
                    <p class="eyebrow eyebrow--gold">Explore more</p>
                    <h2 id="related-title">Other massage treatments.</h2>
                </div>
                <div class="related-grid">
                    @foreach ($otherTreatments as $otherSlug => $other)
                        <a class="related-card reveal" href="{{ route('treatments.show', $otherSlug) }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $other['title'] }} details in a new tab">
                            <x-responsive-image
                                src="{{ $other['image'] }}"
                                alt="{{ $other['title'] }} treatment at Angel Touch Therapy in Harrogate"
                                sizes="(max-width: 560px) calc(100vw - 36px), (max-width: 820px) 50vw, 33vw"
                                loading="lazy"
                                decoding="async"
                                fetchpriority="low"
                            />
                            <span><small>{{ $other['number'] }}</small><strong>{{ $other['title'] }}</strong><b aria-hidden="true">↗</b></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container site-footer__top">
            <a class="brand brand--footer" href="{{ route('home') }}" aria-label="Angel Touch Therapy home">
                <img class="brand__mark" src="{{ asset('images/angel-touch-mark.webp') }}" alt="Angel Touch Therapy logo" loading="lazy" decoding="async" width="600" height="310">
                <span><strong>Angel Touch</strong><small>Therapy · Harrogate</small></span>
            </a>
            <p>Sports Massage · Deep Tissue Massage<br>Swedish Massage · Full Body Massage</p>
            <div class="social-links">
                <a href="https://www.instagram.com/angeltouch_massages_hg/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
                <a href="https://www.youtube.com/channel/UCE2vtnQQMdXAqbUktxCp5wQ/videos" target="_blank" rel="noopener noreferrer">YouTube <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
                <a href="https://www.facebook.com/angeltouchtreatment/" target="_blank" rel="noopener noreferrer">Facebook <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
                <a href="https://www.tiktok.com/@angel.touch.harrogate" target="_blank" rel="noopener noreferrer">TikTok <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
            </div>
        </div>
        <div class="container site-footer__bottom">
            <span>© {{ date('Y') }} Angel Touch Therapy</span>
            <span>Professional and confidential massage therapy in Harrogate</span>
            <div class="site-footer__legal"><a href="{{ route('privacy-policy') }}">Privacy policy</a><a href="{{ route('terms-and-conditions') }}">Terms &amp; conditions</a><button class="cookie-settings" type="button" data-cookie-settings>Cookie settings</button></div>
        </div>
    </footer>

    @include('cookie-consent')

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
