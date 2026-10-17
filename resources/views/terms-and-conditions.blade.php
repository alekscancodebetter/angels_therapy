<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $canonicalUrl = rtrim((string) config('app.canonical_url'), '/').route('terms-and-conditions', [], false);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Terms &amp; Conditions | Angel Touch Therapy">
    <meta name="description" content="Terms and conditions for massage therapy appointments, bookings, payments and cancellations with Angel Touch Therapy in Harrogate.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Angel Touch Therapy">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="Terms &amp; Conditions | Angel Touch Therapy">
    <meta property="og:description" content="The terms that apply when you book and receive a massage treatment from Angel Touch Therapy.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ asset('images/angel-touch-hero.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Terms &amp; Conditions | Angel Touch Therapy">
    <meta name="twitter:description" content="The terms that apply when you book and receive a massage treatment from Angel Touch Therapy.">
    <meta name="twitter:image" content="{{ asset('images/angel-touch-hero.png') }}">
    <title>Terms &amp; Conditions | Angel Touch Therapy</title>
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('favicons')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body class="privacy-page">
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
        <section class="privacy-hero" aria-labelledby="terms-title">
            <div class="container privacy-hero__content">
                <p class="eyebrow">Booking and treatment</p>
                <h1 id="terms-title">Terms &amp; Conditions</h1>
                <p>These terms explain what you can expect when you book and receive a massage treatment from Angel Touch Therapy.</p>
                <p class="privacy-hero__updated">Last updated: <time datetime="2026-10-16">16 October 2026</time></p>
            </div>
        </section>

        <section class="privacy-content section section--ivory">
            <div class="container privacy-layout">
                <aside class="privacy-nav" aria-label="Terms and conditions contents">
                    <p>On this page</p>
                    <nav>
                        <a href="#about-these-terms">About these terms</a>
                        <a href="#bookings">Bookings</a>
                        <a href="#prices-and-payment">Prices and payment</a>
                        <a href="#changes-and-cancellations">Changes and cancellations</a>
                        <a href="#health-and-suitability">Health and suitability</a>
                        <a href="#appointments">At your appointment</a>
                        <a href="#consumer-rights">Your consumer rights</a>
                        <a href="#liability">Our responsibility</a>
                        <a href="#website-and-privacy">Website and privacy</a>
                        <a href="#contact-and-law">Contact and applicable law</a>
                    </nav>
                </aside>

                <article class="privacy-copy">
                    <section id="about-these-terms">
                        <p class="privacy-section-number">01</p>
                        <h2>About these terms</h2>
                        <p>These terms apply to massage treatments and appointment bookings supplied by Angel Touch Therapy. Please read them before booking. When you submit an online booking and accept these terms, you agree that they form part of the contract between you and Angel Touch Therapy.</p>
                        <div class="privacy-contact-card">
                            <strong>Angel Touch Therapy</strong>
                            <span>NuDerma Clinic</span>
                            <span>137 Wetherby Road</span>
                            <span>Harrogate, North Yorkshire, HG2 7AA</span>
                            <a href="tel:+447915910033">(+44) 7915 910 033</a>
                        </div>
                    </section>

                    <section id="bookings">
                        <p class="privacy-section-number">02</p>
                        <h2>Bookings and confirmation</h2>
                        <p>Please provide complete and accurate contact and booking information. An online booking is accepted when the website displays a successful booking confirmation. If an appointment cannot be provided because of an error or circumstances outside our reasonable control, we will contact you promptly and offer a suitable alternative or cancellation.</p>
                        <p>Bookings are personal to the client named in the booking. If the treatment is for somebody else, please enter that person’s name and make sure they are aware of these terms. If the client is under 18, contact Villy before booking so that suitable consent and attendance arrangements can be agreed.</p>
                    </section>

                    <section id="prices-and-payment">
                        <p class="privacy-section-number">03</p>
                        <h2>Prices and payment</h2>
                        <p>The price shown when you book is the price for the selected treatment duration:</p>
                        <ul>
                            @foreach (config('booking.duration_options') as $option)
                                <li><strong>{{ $option['label'] }}:</strong> £{{ $option['price'] }}</li>
                            @endforeach
                            <li><strong>{{ config('booking.package.label') }}:</strong> £{{ config('booking.package.price') }}, {{ strtolower(config('booking.package.note')) }}. Package sessions are arranged individually.</li>
                        </ul>
                        <p>The website does not take payment. Payment arrangements will be confirmed directly with you. If a price displayed on the website is clearly incorrect, we will tell you before the treatment and you may accept the correct price or cancel without charge.</p>
                    </section>

                    <section id="changes-and-cancellations">
                        <p class="privacy-section-number">04</p>
                        <h2>Changes and cancellations</h2>
                        <p>If you need to cancel or rearrange an appointment, please call <a href="tel:+447915910033">07915 910 033</a> as soon as possible and, where you can, give at least 24 hours’ notice. The website does not automatically charge a cancellation or missed-appointment fee.</p>
                        <p>If Angel Touch Therapy needs to cancel or rearrange your appointment, we will give as much notice as reasonably possible. You may accept a new appointment or cancel. Any payment already made for a treatment that is not provided will be refunded unless you choose to transfer it to a new appointment.</p>
                        <div class="privacy-note">
                            <strong>Prepaid packages</strong>
                            <p>When a prepaid package is cancelled, any refund or deduction will be handled fairly and will reflect treatments already provided and any reasonable, direct loss. We will not keep an advance payment for a service that we cancel and do not provide.</p>
                        </div>
                    </section>

                    <section id="health-and-suitability">
                        <p class="privacy-section-number">05</p>
                        <h2>Health and suitability</h2>
                        <p>Massage therapy is not a substitute for medical diagnosis or treatment. You are responsible for sharing information during your consultation that is relevant to a safe and suitable treatment, including any injury, medical condition, pregnancy, allergy, medication or recent procedure that may affect the treatment.</p>
                        <p>Villy may adapt, postpone, refuse or stop a treatment where this is reasonably necessary for safety, professional standards or appropriate conduct. Where appropriate, you may be asked to obtain advice or approval from a qualified healthcare professional before treatment.</p>
                        <p>Please do not include detailed medical information in the online booking message. Relevant information can be discussed privately during your consultation.</p>
                    </section>

                    <section id="appointments">
                        <p class="privacy-section-number">06</p>
                        <h2>At your appointment</h2>
                        <p>Please arrive on time. If you arrive late, we will try to provide the booked treatment but may need to shorten it so that later clients are not delayed. We will explain any change before treatment begins.</p>
                        <p>Clients and staff must behave respectfully. Sexual, threatening, abusive or discriminatory behaviour is not accepted. A treatment may be ended immediately where conduct makes it unsafe or inappropriate to continue.</p>
                        <p>Results vary from person to person. No particular therapeutic outcome is promised, but every treatment will be provided with reasonable care and skill.</p>
                    </section>

                    <section id="consumer-rights">
                        <p class="privacy-section-number">07</p>
                        <h2>Your consumer rights</h2>
                        <p>Nothing in these terms limits your rights under consumer law. Services must be provided with reasonable care and skill, within a reasonable time where no time has been agreed, and for a reasonable price where no price has been agreed.</p>
                        <p>If you book at a distance, such as online or by telephone, you may have a statutory right to cancel the contract. Where the 14-day cancellation period applies, it normally begins when the contract is made. If you ask for a service to begin during that period and then cancel after part of it has been provided, the law may allow a reasonable proportion of the price to be charged. Exceptions may apply depending on the service and booking circumstances.</p>
                        <p>To exercise a cancellation right, call us using the details on this page and make a clear statement that you wish to cancel. These terms do not replace or reduce any cancellation, repeat-performance, price-reduction or refund right given to you by law.</p>
                    </section>

                    <section id="liability">
                        <p class="privacy-section-number">08</p>
                        <h2>Our responsibility</h2>
                        <p>Angel Touch Therapy is responsible for loss or damage that is a foreseeable result of breaching these terms or failing to use reasonable care and skill. We are not responsible for loss or damage that could not reasonably have been foreseen when the contract was made, or for business losses where the service was booked for personal use.</p>
                        <p>Nothing in these terms excludes or limits responsibility for death or personal injury caused by negligence, fraud or fraudulent misrepresentation, or any responsibility that cannot legally be excluded or limited.</p>
                    </section>

                    <section id="website-and-privacy">
                        <p class="privacy-section-number">09</p>
                        <h2>Website use and privacy</h2>
                        <p>The website is provided for general information and appointment booking. We take reasonable care to keep it available and accurate but cannot promise uninterrupted access. Content, branding and images owned by Angel Touch Therapy may not be copied or used commercially without permission.</p>
                        <p>External websites, including Google, YouTube, Instagram and Skype, have their own terms and privacy practices. How we handle personal information is explained in our <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.</p>
                    </section>

                    <section id="contact-and-law">
                        <p class="privacy-section-number">10</p>
                        <h2>Questions, complaints and applicable law</h2>
                        <p>If you have a question or complaint, please call <a href="tel:+447915910033">07915 910 033</a> or use the <a href="{{ route('home') }}#contact">contact details on our home page</a>. We will try to investigate and resolve the matter fairly and promptly.</p>
                        <p>These terms are governed by the law of England and Wales. You may bring proceedings in the courts of England and Wales. If you live in Scotland or Northern Ireland, you may also bring proceedings in your local courts. Any mandatory consumer protection that applies where you live remains unaffected.</p>
                        <p>We may update these terms when our services, prices, website or legal responsibilities change. The terms in force when your booking is accepted will apply to that booking.</p>
                    </section>
                </article>
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
            <div class="site-footer__legal"><a href="{{ route('privacy-policy') }}">Privacy policy</a><a href="{{ route('terms-and-conditions') }}" aria-current="page">Terms &amp; conditions</a><button class="cookie-settings" type="button" data-cookie-settings>Cookie settings</button></div>
        </div>
    </footer>

    @include('cookie-consent')

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
