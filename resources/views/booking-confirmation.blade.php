<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $canonicalUrl = rtrim((string) config('app.canonical_url'), '/').route('booking-confirmation', [], false);
        $confirmation = session('booking_confirmation');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Booking confirmed | Angel Touch Therapy">
    <meta name="description" content="Your massage appointment with Angel Touch Therapy has been booked.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Angel Touch Therapy">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="Booking confirmed | Angel Touch Therapy">
    <meta property="og:description" content="Your massage appointment with Angel Touch Therapy has been booked.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <title>Booking confirmed | Angel Touch Therapy</title>
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('favicons')
    @include('google-ads', ['bookingCompleted' => true])
    @include('meta-ads', ['bookingCompleted' => true])
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body class="confirmation-page">
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <a class="brand" href="{{ route('home') }}" aria-label="Angel Touch Therapy home">
            <img class="brand__mark" src="{{ asset('images/angel-touch-mark.webp') }}" alt="Angel Touch Therapy logo" width="600" height="310">
            <span><strong>Angel Touch</strong><small>Therapy · Harrogate</small></span>
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
        <section class="confirmation-hero" aria-labelledby="confirmation-title">
            <div class="confirmation-hero__orb confirmation-hero__orb--one" aria-hidden="true"></div>
            <div class="confirmation-hero__orb confirmation-hero__orb--two" aria-hidden="true"></div>
            <div class="container confirmation-hero__content">
                <p class="eyebrow eyebrow--gold">Booking confirmed</p>
                <div class="confirmation-card">
                    <span class="confirmation-card__mark" aria-hidden="true">✓</span>
                    <h1 id="confirmation-title">Thank you for booking with us.</h1>
                    <p class="confirmation-card__intro">Your appointment has been reserved. We look forward to welcoming you to Angel Touch Therapy.</p>

                    @if (is_array($confirmation))
                        <dl class="confirmation-details" aria-label="Appointment details">
                            <div><dt>Treatment length</dt><dd>{{ $confirmation['duration'] }} minutes</dd></div>
                            <div><dt>Date</dt><dd>{{ $confirmation['date'] }}</dd></div>
                            <div><dt>Time</dt><dd>{{ $confirmation['time'] }}</dd></div>
                        </dl>
                    @endif

                    <p class="confirmation-card__note">Please call 07915 910 033 if you need to change your appointment.</p>
                    <div class="confirmation-card__actions">
                        <a class="button button--gold" href="{{ route('home') }}">Back to the homepage</a>
                        <a class="confirmation-card__phone" href="tel:+447915910033">Call Villy <strong>07915 910 033</strong></a>
                    </div>
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
