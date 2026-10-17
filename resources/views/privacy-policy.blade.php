<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $canonicalUrl = rtrim((string) config('app.canonical_url'), '/').route('privacy-policy', [], false);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Privacy Policy | Angel Touch Therapy">
    <meta name="description" content="Privacy policy for Angel Touch Therapy in Harrogate, including how personal information is collected, used, stored and protected.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Angel Touch Therapy">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="Privacy Policy | Angel Touch Therapy">
    <meta property="og:description" content="How Angel Touch Therapy collects, uses, stores and protects personal information.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ asset('images/angel-touch-hero.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Privacy Policy | Angel Touch Therapy">
    <meta name="twitter:description" content="How Angel Touch Therapy collects, uses, stores and protects personal information.">
    <meta name="twitter:image" content="{{ asset('images/angel-touch-hero.png') }}">
    <title>Privacy Policy | Angel Touch Therapy</title>
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
        <section class="privacy-hero" aria-labelledby="privacy-title">
            <div class="container privacy-hero__content">
                <p class="eyebrow">Your information</p>
                <h1 id="privacy-title">Privacy Policy</h1>
                <p>This notice explains how Angel Touch Therapy collects, uses, stores and protects your personal information.</p>
                <p class="privacy-hero__updated">Last updated: <time datetime="2026-10-16">16 October 2026</time></p>
            </div>
        </section>

        <section class="privacy-content section section--ivory">
            <div class="container privacy-layout">
                <aside class="privacy-nav" aria-label="Privacy policy contents">
                    <p>On this page</p>
                    <nav>
                        <a href="#who-we-are">Who we are</a>
                        <a href="#information-we-collect">Information we collect</a>
                        <a href="#how-we-use-information">How we use it</a>
                        <a href="#sharing-information">Who we share it with</a>
                        <a href="#cookies">Cookies and external content</a>
                        <a href="#retention">How long we keep it</a>
                        <a href="#your-rights">Your rights</a>
                        <a href="#complaints">Questions and complaints</a>
                    </nav>
                </aside>

                <article class="privacy-copy">
                    <section id="who-we-are">
                        <p class="privacy-section-number">01</p>
                        <h2>Who we are</h2>
                        <p>Angel Touch Therapy is the controller responsible for the personal information described in this policy.</p>
                        <div class="privacy-contact-card">
                            <strong>Angel Touch Therapy</strong>
                            <span>NuDerma Clinic</span>
                            <span>137 Wetherby Road</span>
                            <span>Harrogate, North Yorkshire, HG2 7AA</span>
                            <a href="tel:+447915910033">(+44) 7915 910 033</a>
                        </div>
                    </section>

                    <section id="information-we-collect">
                        <p class="privacy-section-number">02</p>
                        <h2>Information we collect</h2>
                        <p>We collect only the information needed to manage enquiries, appointments and treatments, operate the website and meet our professional and legal responsibilities. This may include:</p>
                        <ul>
                            <li><strong>Contact details</strong>, such as your name, email address and telephone number.</li>
                            <li><strong>Booking details</strong>, including the treatment requested, appointment date and time, and messages you send with a booking or enquiry.</li>
                            <li><strong>Treatment and consultation information</strong> that is relevant to providing a safe, suitable service. This can include health information, which receives additional protection under data protection law.</li>
                            <li><strong>Correspondence</strong>, including questions, requests, complaints and any follow-up communication.</li>
                            <li><strong>Public review information</strong>, such as your display name and review text when you choose to publish a review on Google.</li>
                            <li><strong>Technical information</strong> needed to deliver and secure the website, such as your IP address, browser or device information, request times and security logs.</li>
                        </ul>
                        <div class="privacy-note">
                            <strong>Please keep online booking notes brief.</strong>
                            <p>Do not include detailed medical information in the optional booking message. Relevant health information can be discussed privately during your consultation.</p>
                        </div>
                    </section>

                    <section id="how-we-use-information">
                        <p class="privacy-section-number">03</p>
                        <h2>How and why we use your information</h2>
                        <div class="privacy-table-wrap">
                            <table>
                                <thead>
                                    <tr><th>What we do</th><th>Why we do it</th><th>Lawful basis</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Respond to enquiries and manage bookings</td><td>To take steps you request and arrange your appointment</td><td>Contract, or steps before entering a contract</td></tr>
                                    <tr><td>Provide and tailor treatments</td><td>To understand relevant needs and deliver the requested service safely</td><td>Contract; where health information is needed, we will identify an appropriate special-category condition, such as your explicit consent</td></tr>
                                    <tr><td>Keep business and treatment records</td><td>To meet tax, accounting, insurance, professional and legal responsibilities, and to establish or defend legal claims</td><td>Legal obligation and legitimate interests</td></tr>
                                    <tr><td>Operate and secure the website</td><td>To prevent misuse, troubleshoot problems and keep booking and administration features working</td><td>Legitimate interests in running a secure and reliable service</td></tr>
                                    <tr><td>Display public Google reviews</td><td>To help visitors understand other clients’ experiences</td><td>Legitimate interests in presenting public feedback about the business</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <p>If we ask for explicit consent to use health information, you may withdraw that consent at any time. Withdrawal will not affect processing that was lawful before it was withdrawn. We do not use your information for automated decision-making or profiling, and we do not sell personal information.</p>
                    </section>

                    <section id="sharing-information">
                        <p class="privacy-section-number">04</p>
                        <h2>Who we share information with</h2>
                        <p>We may share personal information only where necessary with:</p>
                        <ul>
                            <li>trusted providers that host, maintain or support the website, database and business systems;</li>
                            <li>professional advisers, insurers or regulators where this is necessary;</li>
                            <li>healthcare or emergency professionals where you ask us to do so, where we have another lawful reason, or where this is necessary to protect someone’s vital interests; and</li>
                            <li>courts, law-enforcement bodies or public authorities where disclosure is required by law.</li>
                        </ul>
                        <p>Service providers may only use information for the services they provide to us and must protect it appropriately. If information is transferred outside the UK, we take steps to ensure an appropriate safeguard or other lawful transfer mechanism is in place.</p>
                    </section>

                    <section id="cookies">
                        <p class="privacy-section-number">05</p>
                        <h2>Cookies, browser storage and external content</h2>
                        <p>The website uses strictly necessary session and security cookies so that forms and the administration area work securely. Your cookie preference is saved in your browser’s local storage under <code>angel-touch-cookie-consent-v1</code>. You can remove it through your browser settings or reopen the notice using the “Cookie settings” button in the footer.</p>
                        <p>We do not use advertising or analytics cookies. Video preview images are supplied by YouTube. A privacy-enhanced YouTube player is loaded only after you choose to play a video; YouTube may then receive technical information and set or access its own storage. Links to Google Maps, Google Reviews, Instagram and YouTube take you to services with their own privacy policies.</p>
                    </section>

                    <section id="retention">
                        <p class="privacy-section-number">06</p>
                        <h2>How long we keep information</h2>
                        <p>We keep personal information only for as long as it is needed for the purpose for which it was collected. The period depends on the type of record and may be affected by tax, insurance, professional, safeguarding and legal-claims requirements.</p>
                        <p>Enquiries that do not lead to a booking are deleted when they are no longer needed. Appointment, customer and treatment records are reviewed and retained only while needed to provide services or meet the responsibilities described above. Technical and security logs are kept for the limited period needed to operate and protect the website. Information is securely deleted or anonymised when it is no longer required.</p>
                    </section>

                    <section id="your-rights">
                        <p class="privacy-section-number">07</p>
                        <h2>Your data protection rights</h2>
                        <p>Depending on the circumstances and the lawful basis being used, you may have the right to:</p>
                        <ul>
                            <li>ask for a copy of your personal information;</li>
                            <li>ask us to correct inaccurate or incomplete information;</li>
                            <li>ask us to delete information or restrict how it is used;</li>
                            <li>object to processing based on legitimate interests;</li>
                            <li>receive certain information in a portable format; and</li>
                            <li>withdraw consent where consent is the basis for processing.</li>
                        </ul>
                        <div class="privacy-note privacy-note--rights">
                            <strong>Your right to object</strong>
                            <p>You have the right to object to processing based on our legitimate interests. We will stop unless we have compelling legitimate grounds to continue or need the information for legal claims.</p>
                        </div>
                        <p>There is normally no charge for exercising these rights. We may need to confirm your identity before responding.</p>
                    </section>

                    <section id="complaints">
                        <p class="privacy-section-number">08</p>
                        <h2>Questions and complaints</h2>
                        <p>To exercise a right, ask a privacy question or make a data protection complaint, call <a href="tel:+447915910033">07915 910 033</a>, write to the address above, or use the <a href="{{ route('home') }}#contact">contact details on our home page</a>. Please contact us first so we have an opportunity to investigate and resolve your concern.</p>
                        <p>You also have the right to complain to the Information Commissioner’s Office (ICO), the UK regulator for data protection. Visit <a href="https://ico.org.uk/make-a-complaint/" target="_blank" rel="noopener noreferrer">ico.org.uk/make-a-complaint/ <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a> for current guidance.</p>
                    </section>

                    <section id="changes">
                        <p class="privacy-section-number">09</p>
                        <h2>Changes to this policy</h2>
                        <p>We may update this policy when our services, website or legal responsibilities change. The latest version will always appear on this page with its updated date.</p>
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
            </div>
        </div>
        <div class="container site-footer__bottom">
            <span>© {{ date('Y') }} Angel Touch Therapy</span>
            <span>Professional and confidential massage therapy in Harrogate</span>
            <div class="site-footer__legal"><a href="{{ route('privacy-policy') }}" aria-current="page">Privacy policy</a><a href="{{ route('terms-and-conditions') }}">Terms &amp; conditions</a><button class="cookie-settings" type="button" data-cookie-settings>Cookie settings</button></div>
        </div>
    </footer>

    @include('cookie-consent')

    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
