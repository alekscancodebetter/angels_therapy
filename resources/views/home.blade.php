<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $canonicalUrl = rtrim((string) config('app.canonical_url'), '/').route('home', [], false);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="title" content="Angel Touch Therapy | Massage Therapy in Harrogate">
    <meta name="description" content="Angel Touch Therapy in Harrogate offers sports massage, deep tissue massage, Swedish massage and full body massage with Villy, a qualified and fully insured therapist.">
    <meta name="theme-color" content="#2f6f9f">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Angel Touch Therapy">
    <meta property="og:locale" content="en_GB">
    <meta property="og:title" content="Angel Touch Therapy | Massage Therapy in Harrogate">
    <meta property="og:description" content="Professional massage therapy in Harrogate with Villy, a qualified and fully insured therapist.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ asset('images/angel-touch-share-logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/angel-touch-share-logo.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1254">
    <meta property="og:image:height" content="1254">
    <meta property="og:image:alt" content="Angel Touch Therapy logo">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Angel Touch Therapy | Massage Therapy in Harrogate">
    <meta name="twitter:description" content="Professional massage therapy in Harrogate with Villy, a qualified and fully insured therapist.">
    <meta name="twitter:image" content="{{ asset('images/angel-touch-share-logo.png') }}">
    <meta name="twitter:image:alt" content="Angel Touch Therapy logo">
    <title>Angel Touch Therapy | Massage Therapy in Harrogate</title>
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('favicons')
    @include('google-ads', ['bookingCompleted' => false])
    @include('meta-ads', ['bookingCompleted' => false])
    <link
        rel="preload"
        href="{{ asset('images/angel-touch-hero-1280.webp') }}"
        as="image"
        type="image/webp"
        imagesrcset="{{ asset('images/angel-touch-hero-768.webp') }} 768w, {{ asset('images/angel-touch-hero-1280.webp') }} 1280w, {{ asset('images/angel-touch-hero-1536.webp') }} 1536w"
        imagesizes="100vw"
        fetchpriority="high"
    >
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <a class="brand" href="#top" aria-label="Angel Touch Therapy home">
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
                    <a href="#treatments"><small aria-hidden="true">→</small><span>All treatments</span></a>
                    @foreach (config('treatments') as $treatmentSlug => $navTreatment)
                        <a href="{{ route('treatments.show', $treatmentSlug) }}"><small aria-hidden="true">→</small><span>{{ $navTreatment['title'] }}</span></a>
                    @endforeach
                </div>
            </details>
            <a href="#about">About Villy</a>
            <a href="#stories">Reviews</a>
            <a href="#contact">Contact</a>
            <a class="nav-booking-link" href="#booking">
                <span><strong>Book now</strong><small>Choose your appointment</small></span>
                <span aria-hidden="true">→</span>
            </a>
        </nav>

        <div class="header-actions">
            <a class="phone-link" href="tel:+447915910033" aria-label="Call Angel Touch Therapy on 07915 910033"><span>Call now</span><strong>07915 910 033</strong></a>
            <a class="button button--light" href="#booking">Book appointment</a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" data-menu-toggle>
                <span></span><span></span><span></span><b data-menu-label>Open menu</b>
            </button>
        </div>
    </header>

    <main id="main">
        <section class="hero" id="top" aria-labelledby="hero-title">
            <x-responsive-image
                class="hero__image"
                src="images/angel-touch-hero.png"
                alt="Warm massage treatment room at Angel Touch Therapy in Harrogate"
                sizes="100vw"
                fetchpriority="high"
            />
            <div class="hero__veil"></div>
            <div class="hero__content container">
                <p class="eyebrow">Sports massage · Deep tissue · Swedish · Full body</p>
                <h1 id="hero-title">Relax. Rebalance.<br><em>Rejuvenate.</em> Recover.</h1>
                <p class="hero__intro">Professional massage therapy in Harrogate, tailored to help relieve tension, soothe the body and support recovery.</p>
                <div class="hero__actions">
                    <a class="button button--gold" href="#booking">Book an appointment</a>
                    <a class="text-link text-link--light" href="#treatments">Explore treatments <span aria-hidden="true">↓</span></a>
                </div>
            </div>
            <div class="hero__aside" aria-label="Practice details">
                <span>NuDerma Clinic</span><span>137 Wetherby Road</span><span>Harrogate · HG2 7AA</span>
            </div>
        </section>

        <section class="intro section section--ivory" aria-labelledby="intro-title">
            <div class="container intro__grid reveal">
                <p class="section-index">01 · Angel Touch</p>
                <div>
                    <p class="eyebrow eyebrow--dark">Luxury, quality &amp; comfort</p>
                    <h2 id="intro-title">A personal approach to feeling better in your body.</h2>
                </div>
                <blockquote>
                    <p>“What inspires me most is seeing a smile after a successful treatment. Helping people recover from sports injuries or other trauma, and relieving pain, is my greatest reward.”</p>
                    <footer>Villy <span>— Your Angel Touch therapist</span></footer>
                </blockquote>
            </div>
        </section>

        @include('google-reviews', [
            'sectionId' => 'stories',
            'heading' => 'Real stories from Google.',
            'reviewGroup' => 'home',
        ])

        <section class="treatments section" aria-labelledby="treatments-title">
            <div class="container">
                <div class="treatments__intro reveal">
                    <p class="eyebrow eyebrow--gold anchor-target" id="treatments">Massage treatments</p>
                    <h2 id="treatments-title">Choose the treatment<br>that feels right for you.</h2>
                    <p>Every treatment includes a full consultation, allowing Villy to tailor the therapy to your needs. Select a massage to learn more.</p>
                </div>

                <div class="treatment-grid">
                    @foreach (config('treatments') as $slug => $treatment)
                        <a class="treatment-card reveal" href="{{ route('treatments.show', $slug) }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $treatment['title'] }} details in a new tab">
                            <x-responsive-image
                                src="{{ $treatment['image'] }}"
                                alt="{{ $treatment['title'] }} treatment at Angel Touch Therapy in Harrogate"
                                sizes="(max-width: 560px) calc(100vw - 36px), (max-width: 820px) calc(50vw - 27px), 58vw"
                                loading="lazy"
                                decoding="async"
                                fetchpriority="low"
                            />
                            <span class="treatment-card__shade"></span>
                            <span class="treatment-card__number">{{ $treatment['number'] }}</span>
                            <span class="treatment-card__content">
                                <small>{{ $treatment['kicker'] }}</small>
                                <strong>{{ $treatment['title'] }}</strong>
                            </span>
                            <span class="treatment-card__arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>

                <div class="treatment-pricing reveal" aria-label="Massage prices">
                    @foreach (config('booking.duration_options') as $duration => $option)
                        <article><small>{{ $option['label'] }}</small><strong>£{{ $option['price'] }}</strong><span>Every massage treatment</span></article>
                    @endforeach
                    <article class="treatment-pricing__package"><small>{{ config('booking.package.label') }}</small><strong>£{{ config('booking.package.price') }}</strong><span>{{ config('booking.package.note') }}</span></article>
                </div>
                <div class="treatments__note reveal">
                    <span>Full consultation included</span>
                    <span>Package sessions are booked individually</span>
                </div>
            </div>
        </section>

        <section class="conditions section section--sand" aria-labelledby="conditions-title">
            <div class="container conditions__grid">
                <div class="conditions__lead reveal">
                    <p class="eyebrow eyebrow--dark">Areas of treatment</p>
                    <h2 id="conditions-title">Ease tension.<br>Support your body.</h2>
                    <p>Massage therapy is available for a range of concerns and areas of discomfort.</p>
                    <a class="button button--dark" href="#booking">Start your treatment</a>
                </div>
                <ul class="condition-list reveal">
                    <li><span>01</span>Back pain, sciatica &amp; leg pain</li><li><span>02</span>Neck pain</li>
                    <li><span>03</span>Headaches</li><li><span>04</span>Spinal trauma</li>
                    <li><span>05</span>Hand, wrist &amp; elbow pain</li><li><span>06</span>Shoulder pain</li>
                    <li><span>07</span>Ankle, knee &amp; hip pain</li><li><span>08</span>Repetitive strain injury</li>
                    <li><span>09</span>Posture correction</li>
                </ul>
            </div>
        </section>

        <section class="about section" aria-labelledby="about-title">
            <div class="about__image reveal">
                <img
                    class="about__image-media"
                    src="{{ asset('images/villy-massage-treatment.png') }}"
                    alt="Villy providing a leg massage at Angel Touch Therapy in Harrogate"
                    width="540"
                    height="960"
                    loading="lazy"
                    decoding="async"
                    fetchpriority="low"
                />
            </div>
            <div class="about__content reveal">
                <p class="eyebrow eyebrow--gold anchor-target" id="about">About Angel Touch Therapy</p>
                <h2 id="about-title">Qualified, insured and practising since 2011.</h2>
                <p>Angel Touch Therapy is owned and represented by Villy, a qualified and fully insured massage therapist offering sports massage, deep tissue massage, and stress and tension release massage therapy.</p>
                <p>Villy has provided a wide range of complementary therapies at reasonable rates since 2011. Her practice is available to people of all ages — from pre-natal and ante-natal care through birthing assistance, to children, adults and the elderly — with a professional and confidential service.</p>

                <div class="qualifications">
                    <p class="qualifications__label">Training &amp; qualifications</p>
                    <ul>
                        <li>ITEC Level 3 Diploma in Sports Massage</li>
                        <li>ITEC Level 3 Diploma in Holistic Massage</li>
                        <li>Diploma in Meridian and SU–JOK Therapy</li>
                    </ul>
                </div>
                <a class="fht-link" href="https://www.fht.org.uk/" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('images/fht-logo.png') }}" alt="Federation of Holistic Therapists logo" loading="lazy" decoding="async" width="243" height="95">
                    <span class="fht-link__copy">
                        <small>Professional member</small>
                        <strong>Federation of Holistic Therapists</strong>
                    </span>
                    <b aria-hidden="true">↗</b>
                    <span class="visually-hidden">Opens in a new tab</span>
                </a>
            </div>
        </section>

        <section class="expert-quote section section--forest" aria-label="Recommendation from Clare Smith">
            <div class="container expert-quote__grid reveal">
                <div><p class="eyebrow">Live your life to the fullest</p><h2>Treat yourself.</h2></div>
                <blockquote>
                    <p>“Villy is passionate about the wellbeing of the people in her hands. When people feel great, they are free to live life to the full. Villy is an expert in Sports Massage and has been Harrogate-based, working to soothe, deeply relax and restore for several years.</p>
                    <p>The road to achieving better health and vitality is easier than you think. If you want to enhance how you feel about yourself and your life, Villy can help guide and support you. She works skilfully to remove the deep tensions that prevent your body from being at its vital and optimal best.</p>
                    <p>She irons out postural imbalances, aches, pains, niggles and injuries to improve wellbeing and performance. It’s not just for athletes. Whatever you do with it, your body is so important to take care of. Put yourself in her magic ‘Angel Touch’ hands, and she will help you and take care of you.”</p>
                    <footer>Clare Smith <span>— Health Guru</span></footer>
                </blockquote>
            </div>
        </section>

        <section class="videos section" aria-labelledby="videos-title">
            <div class="container">
                <div class="section-heading reveal">
                    <div><p class="eyebrow eyebrow--gold">Video reviews from patients</p><h2 id="videos-title">After the treatment.</h2></div>
                    <p>Nine clients share their own experience of treatment with Villy.</p>
                </div>
                <div class="video-grid reveal">
                    @php
                        $videos = [
                            ['m16YlBleCc0', 'Patrick Altoft'], ['lL8PHcGxXdM', 'James Haddleton'],
                            ['X7Rsklu3_Sw', 'Jonathan Newby'], ['8CEW6OHDrjg', 'Antonia'],
                            ['Y1_JGYECXWk', 'Tom'], ['XI0wWXhz8xk', 'Matt Price'],
                            ['yQ8WvaQZcCY', 'Alison'], ['eZsQl3E-JoQ', 'Tim'], ['hXoVvV3wjtE', 'Kenneth'],
                        ];
                    @endphp
                    @foreach ($videos as [$videoId, $name])
                        <div class="video-card" data-video-card>
                            <button class="video-card__trigger" type="button" data-video="{{ $videoId }}" aria-label="Play {{ $name }}'s video review">
                                <img src="https://i.ytimg.com/vi/{{ $videoId }}/hqdefault.jpg" alt="{{ $name }} video review thumbnail" loading="lazy" decoding="async" fetchpriority="low" width="480" height="360">
                                <span class="video-card__play" aria-hidden="true">▶</span>
                                <span class="video-card__caption"><b>{{ $name }}</b><small>Video review</small></span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="faq section section--ivory" aria-labelledby="faq-title">
            <div class="container">
                <div class="faq__layout" id="faq">
                    <header class="faq__intro reveal">
                        <p class="eyebrow eyebrow--gold anchor-target">Before your visit</p>
                        <h2 id="faq-title">Frequently asked questions.</h2>
                        <p>Everything you need to feel comfortable choosing and booking your treatment.</p>
                    </header>

                    <div class="faq-list reveal">
                        <details>
                            <summary>Which massage should I choose?</summary>
                            <p>Choose the treatment that sounds closest to what you need. Every appointment includes a consultation, so Villy can discuss your goals and tailor the treatment before it begins.</p>
                        </details>
                        <details>
                            <summary>How long is a treatment and how much does it cost?</summary>
                            <p>A {{ config('booking.duration_options.60.label') }} treatment is £{{ config('booking.duration_options.60.price') }} and a {{ config('booking.duration_options.90.label') }} treatment is £{{ config('booking.duration_options.90.price') }}. A prepaid package of {{ config('booking.package.label') }} costs £{{ config('booking.package.price') }}, with each session arranged individually.</p>
                        </details>
                        <details>
                            <summary>What happens at my first appointment?</summary>
                            <p>Your appointment begins with a private consultation about what you would like help with and any relevant health information. Villy then adapts the massage to your needs, comfort and chosen appointment length.</p>
                        </details>
                        <details>
                            <summary>Can I book if I am pregnant, injured or have a medical condition?</summary>
                            <p>Please call before booking if you are unsure whether massage is suitable. Relevant information can be discussed privately during your consultation, and you may be asked to seek advice from a qualified healthcare professional before treatment.</p>
                        </details>
                        <details>
                            <summary>How do I change or cancel an appointment?</summary>
                            <p>Call Villy on <a href="tel:+447915910033">07915 910 033</a> as soon as possible and, where you can, give at least 24 hours’ notice. Full details are available in the <a href="{{ route('terms-and-conditions') }}">Terms &amp; Conditions</a>.</p>
                        </details>
                        <details>
                            <summary>Where is Angel Touch Therapy?</summary>
                            <p>Appointments take place at NuDerma Clinic, 137 Wetherby Road, Harrogate, North Yorkshire, HG2 7AA. The practice is open Monday to Friday, 8am–7pm.</p>
                        </details>
                    </div>
                </div>

                <aside class="faq-cta reveal" aria-label="Book an appointment">
                    <div>
                        <p class="eyebrow">Ready when you are</p>
                        <h3>Choose a time that works for you.</h3>
                        <p>View live availability and book your massage appointment online in a few simple steps.</p>
                    </div>
                    <div class="faq-cta__actions">
                        <a class="button button--gold" href="#booking">Choose an appointment</a>
                        <a class="faq-cta__phone" href="tel:+447915910033">Prefer to talk? <strong>07915 910 033</strong></a>
                    </div>
                </aside>
            </div>
        </section>

        <section class="contact section section--forest" aria-labelledby="booking-title">
            <div class="container">
                <header class="booking-heading reveal">
                    <div><p class="eyebrow anchor-target" id="booking">Book online</p><h2 id="booking-title">Book your treatment.</h2></div>
                    <p>Tell Villy who the appointment is for, then choose a free date and time from the live practice calendar.</p>
                </header>

                @if (session('booking_status'))<div class="form-status booking-status" role="status" aria-atomic="true">{{ session('booking_status') }}</div>@endif
                @if ($errors->any())
                    <div class="form-errors booking-status" id="booking-errors" role="alert" tabindex="-1" data-booking-errors>
                        <p><strong>We could not complete your booking.</strong> Please check the highlighted details below.</p>
                    </div>
                @endif

                @php
                    $oldStartsAt = (string) old('starts_at', '');
                    $bookingMonth = preg_match('/^\d{4}-\d{2}/', $oldStartsAt) ? substr($oldStartsAt, 0, 7) : now()->format('Y-m');
                    $bookingTreatment = (string) old('treatment_slug', '');
                    $bookingTreatmentLabel = config('treatments')[$bookingTreatment]['title'] ?? 'Choose your treatment';
                    $bookingDuration = (int) old('duration_minutes', config('booking.duration_minutes'));
                    $bookingDurationOption = config('booking.duration_options')[$bookingDuration];
                    $bookingDurationLabel = $bookingDurationOption['label'].' — £'.$bookingDurationOption['price'];
                @endphp
                <form
                    class="booking-layout reveal"
                    method="POST"
                    action="{{ route('bookings.store') }}"
                    data-booking-calendar
                    data-booking-form
                    data-availability-url="{{ route('appointments.availability') }}"
                    data-initial-month="{{ $bookingMonth }}"
                    data-selected-slot="{{ old('starts_at') }}"
                >
                    @csrf
                    <input type="hidden" name="starts_at" value="{{ old('starts_at') }}" data-booking-input>
                    <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>

                    <section class="contact-form-wrap booking-form-panel" aria-labelledby="booking-details-title">
                        <div class="booking-calendar__step booking-calendar__step--dark"><span>01</span><div><small>Your details</small><h3 id="booking-details-title">Who is the treatment for?</h3></div></div>
                        <div class="contact-form booking-form">
                            <div class="booking-form__row">
                                <fieldset class="booking-choice-field" @error('treatment_slug') aria-describedby="treatment_slug_error" @enderror>
                                    <legend>Treatment</legend>
                                    <label class="visually-hidden" for="treatment_slug">Treatment</label>
                                    <details class="booking-dropdown" data-booking-dropdown data-booking-placeholder="Choose your treatment" @error('treatment_slug') aria-invalid="true" open @enderror>
                                        <summary><span data-booking-dropdown-label>{{ $bookingTreatmentLabel }}</span><span class="booking-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
                                        <div class="booking-dropdown__menu" role="group" aria-label="Treatment options">
                                            @foreach (config('treatments') as $slug => $treatment)
                                                <label class="booking-dropdown__option">
                                                    <input type="radio" name="treatment_slug" value="{{ $slug }}" data-booking-dropdown-option data-booking-option-label="{{ $treatment['title'] }}" required @checked($bookingTreatment === $slug) @if ($loop->first) id="treatment_slug" @error('treatment_slug') aria-invalid="true" aria-describedby="treatment_slug_error" @enderror @endif>
                                                    <span>{{ $treatment['title'] }}</span><b aria-hidden="true">✓</b>
                                                </label>
                                            @endforeach
                                        </div>
                                    </details>
                                    @error('treatment_slug')<small id="treatment_slug_error">{{ $message }}</small>@enderror
                                </fieldset>
                                <fieldset class="booking-choice-field" @error('duration_minutes') aria-describedby="duration_minutes_error" @enderror>
                                    <legend>Duration &amp; price</legend>
                                    <label class="visually-hidden" for="duration_minutes">Duration and price</label>
                                    <details class="booking-dropdown" data-booking-dropdown data-booking-placeholder="Choose a duration" @error('duration_minutes') aria-invalid="true" open @enderror>
                                        <summary><span data-booking-dropdown-label>{{ $bookingDurationLabel }}</span><span class="booking-dropdown__icon" aria-hidden="true"><svg viewBox="0 0 20 20" focusable="false"><path d="m5 7.5 5 5 5-5" /></svg></span></summary>
                                        <div class="booking-dropdown__menu" role="group" aria-label="Duration and price options">
                                            @foreach (config('booking.duration_options') as $duration => $option)
                                                <label class="booking-dropdown__option">
                                                    <input type="radio" name="duration_minutes" value="{{ $duration }}" data-booking-dropdown-option data-booking-duration data-booking-option-label="{{ $option['label'] }} — £{{ $option['price'] }}" @checked($bookingDuration === $duration) @if ($loop->first) id="duration_minutes" @error('duration_minutes') aria-invalid="true" aria-describedby="duration_minutes_error" @enderror @endif>
                                                    <span>{{ $option['label'] }} <small>£{{ $option['price'] }}</small></span><b aria-hidden="true">✓</b>
                                                </label>
                                            @endforeach
                                        </div>
                                    </details>
                                    @error('duration_minutes')<small id="duration_minutes_error">{{ $message }}</small>@enderror
                                </fieldset>
                            </div>
                            <p class="booking-package-note"><strong>Prepaid package:</strong> {{ config('booking.package.label') }} for £{{ config('booking.package.price') }}. Mention the package in your message or contact Villy to arrange it.</p>
                            <div class="booking-form__row booking-form__row--contact">
                                <label for="name"><span>Name</span><input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required @error('name') aria-invalid="true" aria-describedby="name_error" @enderror>@error('name')<small id="name_error">{{ $message }}</small>@enderror</label>
                                <x-phone-input />
                            </div>
                            <label for="email"><span>Email address</span><input id="email" name="email" type="email" inputmode="email" value="{{ old('email') }}" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="email_error" @enderror>@error('email')<small id="email_error">{{ $message }}</small>@enderror</label>
                            <label for="message"><span>Anything Villy should know? <em>Optional</em></span><textarea id="message" name="message" rows="4" maxlength="2000" aria-describedby="message_help @error('message') message_error @enderror" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea><small class="booking-field-help" id="message_help">Up to 2,000 characters. Please do not include detailed medical information.</small>@error('message')<small id="message_error">{{ $message }}</small>@enderror</label>
                            <p class="booking-privacy-note">We use your details to manage your appointment. Please do not include detailed medical information here; relevant health information can be discussed privately during your consultation. <a href="{{ route('privacy-policy') }}">Read our privacy policy.</a></p>
                        </div>
                    </section>

                    <section class="booking-calendar" aria-labelledby="booking-calendar-title" @error('starts_at') aria-describedby="starts_at_error" @enderror>
                        <div class="booking-calendar__step"><span>02</span><div><small>Preferred time</small><h3 id="booking-calendar-title">Choose your preferred appointment time</h3></div></div>
                        <div class="booking-calendar__toolbar">
                            <button type="button" data-booking-previous aria-label="Previous month">←</button>
                            <strong data-booking-month aria-live="polite">Loading calendar…</strong>
                            <button type="button" data-booking-next aria-label="Next month">→</button>
                        </div>
                        <div class="booking-calendar__weekdays" aria-hidden="true"><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span></div>
                        <div class="booking-calendar__grid" data-booking-days aria-label="Available appointment dates"></div>
                        <div class="booking-calendar__loading" data-booking-loading role="status" aria-live="polite" aria-atomic="true">Checking available appointments…</div>
                        <div class="booking-times__notice" role="note">
                            <span class="booking-times__notice-icon" aria-hidden="true">i</span>
                            <div class="booking-times__notice-content">
                                <p>If your preferred time is already booked, call us and we’ll get back to you to arrange the best available time for you.</p>
                                <a class="booking-times__call phone-link" href="tel:+447915910033" aria-label="Call Angel Touch Therapy"><span>Call now</span></a>
                            </div>
                        </div>
                        <div class="booking-times">
                            <div><small>Preferred time</small><strong data-booking-day>Choose a date above</strong></div>
                            <div class="booking-times__slots" data-booking-times><p>Select a highlighted day to see the times you can request.</p></div>
                        </div>
                        <div class="selected-appointment" aria-live="polite" aria-atomic="true"><small>Selected appointment</small><strong data-selected-appointment>Choose a date and time</strong></div>
                        @error('starts_at')<small class="booking-field-error" id="starts_at_error">{{ $message }}</small>@enderror
                        <label class="booking-terms" for="terms_accepted">
                            <input id="terms_accepted" name="terms_accepted" type="checkbox" value="1" required @checked(old('terms_accepted')) @error('terms_accepted') aria-invalid="true" aria-describedby="terms_accepted_error" @enderror>
                            <span>I have read and accept the <a href="{{ route('terms-and-conditions') }}" target="_blank" rel="noopener noreferrer">Terms &amp; Conditions<span class="visually-hidden"> (opens in a new tab)</span></a>.</span>
                        </label>
                        @error('terms_accepted')<small class="booking-field-error" id="terms_accepted_error">{{ $message }}</small>@enderror
                        <button class="button button--gold booking-submit" type="submit" data-booking-submit @disabled(! old('starts_at'))>Confirm appointment</button>
                    </section>
                </form>

                <div class="practice-info reveal">
                    <div class="contact__details">
                        <p class="eyebrow anchor-target" id="contact">Practice details</p><h2 id="contact-title">Everything you need for your visit.</h2>
                        <p>Your selected appointment will also appear in Villy’s admin calendar immediately.</p>
                    </div>
                    <div class="contact__cards">
                        <a href="tel:+447915910033"><small>Telephone</small><strong>(+44) 7915 910 033</strong></a>
                        <a href="https://www.google.com/maps/search/?api=1&query=NuDerma+Clinic+137+Wetherby+Road+Harrogate+HG2+7AA" target="_blank" rel="noopener noreferrer" aria-label="Open the Angel Touch Therapy practice location in Google Maps in a new tab">
                            <small>Practice</small><strong>NuDerma Clinic<br>137 Wetherby Road<br>Harrogate, North Yorkshire<br>HG2 7AA</strong>
                        </a>
                    </div>
                    <div class="hours">
                        <p>Working hours</p>
                        <dl>
                            <div><dt>Monday</dt><dd>8am – 7pm</dd></div><div><dt>Tuesday</dt><dd>8am – 7pm</dd></div>
                            <div><dt>Wednesday</dt><dd>8am – 7pm</dd></div><div><dt>Thursday</dt><dd>8am – 7pm</dd></div>
                            <div><dt>Friday</dt><dd>8am – 7pm</dd></div>
                        </dl>
                    </div>
                    <figure class="practice-map">
                        <a class="practice-map__image" href="https://www.google.com/maps/search/?api=1&query=NuDerma+Clinic+137+Wetherby+Road+Harrogate+HG2+7AA" target="_blank" rel="noopener noreferrer" aria-label="View the practice map in Google Maps (opens in a new tab)">
                            <img src="{{ asset('images/practice-google-map.png') }}" alt="Google Maps view showing NuDerma Clinic on Wetherby Road near Hookstone Drive in Harrogate" width="1280" height="720" loading="lazy" decoding="async">
                        </a>
                        <figcaption class="practice-map__details">
                            <p class="eyebrow">Plan your visit</p>
                            <h3>Find us at NuDerma Clinic.</h3>
                            <p>137 Wetherby Road<br>Harrogate, North Yorkshire, HG2 7AA</p>
                            <p>Open the interactive map for directions from your location.</p>
                            <a class="button button--gold" href="https://www.google.com/maps/search/?api=1&query=NuDerma+Clinic+137+Wetherby+Road+Harrogate+HG2+7AA" target="_blank" rel="noopener noreferrer">Open in Google Maps <span aria-hidden="true">↗</span><span class="visually-hidden"> (opens in a new tab)</span></a>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container site-footer__top">
            <a class="brand brand--footer" href="#top" aria-label="Back to the top">
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
