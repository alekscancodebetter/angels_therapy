<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Angel Touch Therapy in Harrogate offers sports massage, deep tissue massage, Swedish massage and full body massage with Villy, a qualified and fully insured therapist.">
    <meta name="theme-color" content="#2f6f9f">
    <title>Angel Touch Therapy | Massage Therapy in Harrogate</title>
    <link rel="icon" type="image/png" href="{{ asset('images/angel-touch-mark.png') }}">
    <link rel="preload" href="{{ asset('images/angel-touch-hero.png') }}" as="image" fetchpriority="high">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <a class="brand" href="#top" aria-label="Angel Touch Therapy home">
            <img class="brand__mark" src="{{ asset('images/angel-touch-mark.png') }}" alt="" width="1200" height="620">
            <span><strong>Angel Touch</strong><small>Therapy · Harrogate</small></span>
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Main navigation">
            <a href="#treatments">Treatments</a>
            <a href="#about">About Villy</a>
            <a href="#stories">Reviews</a>
            <a href="#contact">Contact</a>
        </nav>

        <div class="header-actions">
            <a class="phone-link" href="tel:+447915910033" aria-label="Call Angel Touch Therapy on 07915 910033"><span>Call</span> 07915 910 033</a>
            <a class="button button--light" href="#contact">Book appointment</a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav" data-menu-toggle>
                <span></span><span></span><span></span><b>Menu</b>
            </button>
        </div>
    </header>

    <main id="main">
        <section class="hero" id="top" aria-labelledby="hero-title">
            <img class="hero__image" src="{{ asset('images/angel-touch-hero.png') }}" alt="A calm, warmly lit massage treatment room" width="1536" height="864">
            <div class="hero__veil"></div>
            <div class="hero__content container">
                <p class="eyebrow">Sports massage · Deep tissue · Swedish · Full body</p>
                <h1 id="hero-title">Relax. Rebalance.<br><em>Rejuvenate.</em> Recover.</h1>
                <p class="hero__intro">Professional massage therapy in Harrogate, tailored to help relieve tension, soothe the body and support recovery.</p>
                <div class="hero__actions">
                    <a class="button button--gold" href="#contact">Book an appointment</a>
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

        <section class="treatments section" id="treatments" aria-labelledby="treatments-title">
            <div class="container">
                <div class="treatments__intro reveal">
                    <p class="eyebrow eyebrow--gold">Massage treatments</p>
                    <h2 id="treatments-title">Choose the treatment<br>that feels right for you.</h2>
                    <p>Every treatment includes a full consultation, allowing Villy to tailor the therapy to your needs. Select a massage to learn more.</p>
                </div>

                <div class="treatment-grid">
                    @foreach (config('treatments') as $slug => $treatment)
                        <a class="treatment-card reveal" href="{{ route('treatments.show', $slug) }}" aria-label="Learn more about {{ $treatment['title'] }}">
                            <img src="{{ asset($treatment['image']) }}" alt="" loading="lazy" width="1536" height="864">
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

                <div class="treatments__note reveal">
                    <span>Full consultation included</span>
                    <span>Packages available at a discounted price</span>
                </div>
            </div>
        </section>

        <section class="conditions section section--sand" aria-labelledby="conditions-title">
            <div class="container conditions__grid">
                <div class="conditions__lead reveal">
                    <p class="eyebrow eyebrow--dark">Areas of treatment</p>
                    <h2 id="conditions-title">Ease tension.<br>Support your body.</h2>
                    <p>Massage therapy is available for a range of concerns and areas of discomfort.</p>
                    <a class="button button--dark" href="#contact">Start your treatment</a>
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

        <section class="about section" id="about" aria-labelledby="about-title">
            <div class="about__image reveal" role="img" aria-label="Warm, calm massage treatment room"></div>
            <div class="about__content reveal">
                <p class="eyebrow eyebrow--gold">About Angel Touch Therapy</p>
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
                    <img src="{{ asset('images/fht-logo.png') }}" alt="Federation of Holistic Therapists logo" width="243" height="95">
                    <span class="fht-link__copy">
                        <small>Professional member</small>
                        <strong>Federation of Holistic Therapists</strong>
                    </span>
                    <b aria-hidden="true">↗</b>
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

        <section class="stories section section--ivory" id="stories" aria-labelledby="stories-title">
            <div class="container">
                <div class="stories__heading reveal">
                    <div><p class="eyebrow eyebrow--dark">Testimonials</p><h2 id="stories-title">The stories they tell.</h2></div>
                    <div class="slider-controls" aria-label="Testimonial controls">
                        <button type="button" data-story-prev aria-label="Previous testimonial">←</button>
                        <span><b data-story-current>1</b> / 5</span>
                        <button type="button" data-story-next aria-label="Next testimonial">→</button>
                    </div>
                </div>

                <div class="story-slider reveal" data-story-slider aria-live="polite">
                    <article class="story is-active">
                        <p>“I have been seeing Villy for various treatments since 2011. In late autumn 2016 I signed up to the challenge of Mallorca 312, an international cycling Gran Fondo. The challenge involves cycling 312km in a day. I could not have achieved this without the help of Villy. I had regular monthly sessions to prevent any injuries during my heavy training schedule. She helped return my body to normal, enabling me to keep consistent with my training.</p>
                        <p>Less than a week before the event, the cricket season started and I developed a very tight and extremely painful shin. Luckily I had my last session with Villy, and she was able to get me to Mallorca pain free. Thanks again.”</p>
                        <footer>Tom Rowlay</footer>
                    </article>
                    <article class="story" hidden>
                        <p>“A success story. Straining my lower back whilst putting on crampons for a day’s snow climbing in the winter wonderland of Nethermost Cove in the Lake District proved a severe setback to preparations for a much anticipated trip to Nepal and the Everest region. By the time I got home from the Lakes, my back had locked out and I was in considerable pain. This was further aggravated the following day during a long day in Liverpool and an icebound M62 bottleneck.</p>
                        <p>Help was needed to get me mobile again, so I called Guardian Angel Fitness. Villy suggested three sessions of remedial massage. There was an immediate improvement after the first visit, allowing me to ‘walk tall’ again and even cycle to the second appointment. Further improvement allowed me to restart my running club sessions and complete a 25-mile long-distance walking event over Blubberhouses Moor.</p>
                        <p>The third hour of massage was sorely needed as a general tonic. I had regained full movement and could exercise at will again. The next job was to pack my bags and get off to Nepal. Thank you Villy, you worked wonders for me.”</p>
                        <footer>Mike</footer>
                    </article>
                    <article class="story" hidden>
                        <p>“My back problems started in early April. The doctor informed me that I was suffering a muscle spasm and to take painkillers. As there was no improvement I decided to see a chiropractor, but this gave me no pain relief. A friend advised that I try a sports massage with Villy.</p>
                        <p>I booked an appointment and went in without much hope. After the massage I realised that, for the first time since my back problem, things had improved. I visited Villy over the next few weeks and her expertise returned me to full fitness. Many thanks.”</p>
                        <footer>Daryl Bennett</footer>
                    </article>
                    <article class="story" hidden>
                        <p>“My back and right shoulder were in so much pain it hurt to even breathe in. I was having to take tiny breaths because the pain was so bad. One visit to Villy and she had me sorted. The horrible pain was gone and I could breathe normally again.</p>
                        <p>Now I have regular massages with Villy, which help release tension and tightness in my muscles. I always feel great afterwards and the benefits are endless. I always feel in very safe hands with Villy and would recommend her to everybody.”</p>
                        <footer>Hollie</footer>
                    </article>
                    <article class="story" hidden>
                        <p>“I had been at my wits’ end for eight months with pain from an impinged disc at L5. I began seeing Villy five weeks ago on a friend’s recommendation. My friend saw me daily and witnessed my struggle with simple tasks such as walking, carrying things and standing up — things I had taken for granted, but that had become so difficult because of the discomfort.</p>
                        <p>I could not stand straight, straighten my left leg, walk more than a couple of yards without stopping and sitting down, or sit comfortably. I could not get relief. The sessions were challenging at first because I was in so much pain, but Villy was very understanding and ensured that I was comfortable at all times. If something hurt, she would stop or help me move position to alleviate the pain.</p>
                        <p>She also recommended warm baths with magnesium flakes, a magnetic belt to reduce pressure on my discs, and gentle stretches and exercises — all of which I do and which work. I would like to thank Villy and would definitely recommend her massage therapy. Her knowledge and skill are exceptional. I am now walking straight and virtually pain free, much more mobile and able to enjoy my life. I am looking forward to exercising and getting fit again.”</p>
                        <footer>Angela Clarke</footer>
                    </article>
                </div>

                <blockquote class="founder-note reveal">
                    <p>“I am extremely grateful and passionate about my work. It makes me very happy to see a smile on people’s faces, and the remarkable reviews mean so much. Client feedback is especially important to every professional — above all, I want to bring satisfaction and improve how people feel.”</p>
                    <footer>Villy</footer>
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
                        <button class="video-card" type="button" data-video="{{ $videoId }}" aria-label="Play {{ $name }}'s review">
                            <img src="https://i.ytimg.com/vi/{{ $videoId }}/hqdefault.jpg" alt="" loading="lazy" width="480" height="360">
                            <span class="video-card__play" aria-hidden="true">▶</span>
                            <span class="video-card__caption"><b>{{ $name }}</b><small>Video review</small></span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="contact section section--forest" id="contact" aria-labelledby="contact-title">
            <div class="container contact__grid">
                <div class="contact__details reveal">
                    <p class="eyebrow">Get in touch</p><h2 id="contact-title">Book an appointment with Villy.</h2>
                    <p>Relieve tension and give your body the right massage treatment.</p>
                    <div class="contact__cards">
                        <a href="tel:+447915910033"><small>Telephone</small><strong>(+44) 7915 910 033</strong></a>
                        <a href="https://www.google.com/maps/search/?api=1&query=NuDerma+Clinic+137+Wetherby+Road+Harrogate+HG2+7AA" target="_blank" rel="noopener noreferrer">
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
                </div>

                <div class="contact-form-wrap reveal">
                    <p class="contact-form-wrap__label">Appointment request</p>
                    @if (session('status'))<div class="form-status" role="status">{{ session('status') }}</div>@endif
                    @if ($errors->any())<div class="form-errors" role="alert"><p>Please check the highlighted fields.</p></div>@endif

                    <form class="contact-form" method="POST" action="{{ route('appointment-request.store') }}">
                        @csrf
                        <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
                        <label><span>Name</span><input name="name" type="text" value="{{ old('name') }}" autocomplete="name" required @error('name') aria-invalid="true" @enderror>@error('name')<small>{{ $message }}</small>@enderror</label>
                        <label><span>Email address</span><input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required @error('email') aria-invalid="true" @enderror>@error('email')<small>{{ $message }}</small>@enderror</label>
                        <label><span>How can Villy help?</span><textarea name="message" rows="5" required @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</label>
                        <button class="button button--gold" type="submit">Send appointment request</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container site-footer__top">
            <a class="brand brand--footer" href="#top" aria-label="Back to the top">
                <img class="brand__mark" src="{{ asset('images/angel-touch-mark.png') }}" alt="" width="1200" height="620">
                <span><strong>Angel Touch</strong><small>Therapy · Harrogate</small></span>
            </a>
            <p>Sports Massage · Deep Tissue Massage<br>Swedish Massage · Full Body Massage</p>
            <div class="social-links">
                <a href="https://www.instagram.com/angeltouch_massages_hg/" target="_blank" rel="noopener noreferrer">Instagram ↗</a>
                <a href="https://www.youtube.com/channel/UCE2vtnQQMdXAqbUktxCp5wQ/videos" target="_blank" rel="noopener noreferrer">YouTube ↗</a>
                <a href="skype:vili_spasova65?chat">Skype ↗</a>
            </div>
        </div>
        <div class="container site-footer__bottom"><span>© {{ date('Y') }} Angel Touch Therapy</span><span>Professional and confidential massage therapy in Harrogate</span></div>
    </footer>

    <script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
