<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Page Not Found | Angel Touch Therapy">
    <meta name="description" content="The page you requested could not be found on the Angel Touch Therapy website.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="noindex, follow">
    <title>Page Not Found | Angel Touch Therapy</title>
    @include('favicons')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body class="error-page">
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header site-header--solid" data-header>
        <a class="brand" href="{{ route('home') }}" aria-label="Angel Touch Therapy home">
            <img class="brand__mark" src="{{ asset('images/angel-touch-mark.webp') }}" alt="Angel Touch Therapy logo" width="600" height="310">
            <span><strong>Angel Touch</strong><small>Therapy · Harrogate</small></span>
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Main navigation">
            <details class="nav-dropdown">
                <summary>Treatments <span aria-hidden="true">⌄</span></summary>
                <div class="nav-dropdown__menu">
                    <a href="{{ route('home') }}#treatments"><small aria-hidden="true">→</small><span>All treatments</span></a>
                    @foreach (config('treatments') as $treatmentSlug => $treatment)
                        <a href="{{ route('treatments.show', $treatmentSlug) }}"><small aria-hidden="true">→</small><span>{{ $treatment['title'] }}</span></a>
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

    <main id="main" class="error-page__main">
        <section class="error-hero" aria-labelledby="error-title">
            <div class="container error-hero__grid">
                <p class="error-hero__code" aria-hidden="true">404</p>
                <div class="error-hero__copy">
                    <p class="eyebrow">This page is resting</p>
                    <h1 id="error-title">We couldn’t find that page.</h1>
                    <p>The link may be out of date, or the page may have moved. You can return home, explore the massage treatments or go straight to booking.</p>
                    <div class="error-hero__actions">
                        <a class="button button--gold" href="{{ route('home') }}">Return home</a>
                        <a class="text-link text-link--light" href="{{ route('home') }}#treatments">View treatments <span aria-hidden="true">→</span></a>
                    </div>
                    <a class="error-hero__booking" href="{{ route('home') }}#booking">Or choose an available appointment</a>
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
                <a href="skype:vili_spasova65?chat">Skype</a>
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
