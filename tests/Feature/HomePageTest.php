<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_contains_core_practice_information(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sports Massage')
            ->assertSee('Deep Tissue Massage')
            ->assertSee('ITEC Level 3 Diploma in Sports Massage')
            ->assertSee('07915 910 033')
            ->assertSee('137 Wetherby Road')
            ->assertSee('images/angel-touch-mark.webp', false)
            ->assertSee('images/angel-touch-hero-768.webp', false)
            ->assertSee('images/angel-touch-hero-1280.webp', false)
            ->assertSee('images/angel-touch-hero-1536.webp', false)
            ->assertSee('images/sports-massage.png', false)
            ->assertSee('images/deep-tissue-massage.png', false)
            ->assertSee('images/swedish-massage.png', false)
            ->assertSee('images/full-body-massage.png', false)
            ->assertSee('<meta name="title" content="Angel Touch Therapy | Massage Therapy in Harrogate">', false)
            ->assertSee('<meta name="description" content="Angel Touch Therapy in Harrogate offers sports massage, deep tissue massage, Swedish massage and full body massage with Villy, a qualified and fully insured therapist.">', false)
            ->assertSee('<meta property="og:site_name" content="Angel Touch Therapy">', false)
            ->assertSee('<meta property="og:locale" content="en_GB">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
            ->assertSee('<meta name="twitter:title" content="Angel Touch Therapy | Massage Therapy in Harrogate">', false)
            ->assertSee('<meta name="twitter:image" content="'.asset('images/angel-touch-hero.png').'">', false)
            ->assertSee('<meta property="og:url" content="https://angeltouchtherapy.co.uk/">', false)
            ->assertSee('<link rel="canonical" href="https://angeltouchtherapy.co.uk/">', false)
            ->assertSee('css/site.css?v=', false)
            ->assertSee('js/site.js?v=', false)
            ->assertSee('<a class="nav-booking-link" href="#booking">', false)
            ->assertSee('data-cookie-consent', false)
            ->assertSee('href="'.route('privacy-policy').'"', false)
            ->assertSee('href="'.route('terms-and-conditions').'"', false)
            ->assertSee('name="terms_accepted"', false)
            ->assertSee('Read our privacy policy')
            ->assertSee('We use your details to manage your appointment')
            ->assertSee('Frequently asked questions.')
            ->assertSee('Which massage should I choose?')
            ->assertSee('Choose an appointment')
            ->assertSee('href="#booking"', false)
            ->assertSee('Cookies, with your consent.')
            ->assertSee('Essential cookies')
            ->assertSee('External media')
            ->assertSee('Use essential only')
            ->assertSee('Accept all');
    }

    public function test_google_reviews_appear_near_the_top_of_the_page_and_link_to_google(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'Real stories from Google.',
                'Massage treatments',
            ])
            ->assertSee('data-google-reviews-carousel', false)
            ->assertSee('Ryan Gordon')
            ->assertSee('https://share.google/7oFQqDX4FM6MUO6Ox', false)
            ->assertSee('Natasha Eustance')
            ->assertSee('https://share.google/kvsnKwIddPlcgKr5P', false)
            ->assertDontSee('audrey henderson')
            ->assertSee('<span class="google-reviews__position" aria-live="polite" aria-atomic="true">Reviews <b data-google-reviews-current>1–3</b> of 6</span>', false)
            ->assertSee(config('google-reviews.reviews_url'), false)
            ->assertSee('View all 43 Google reviews')
            ->assertSee('https://search.google.com/local/writereview?placeid=ChIJmwsJacNTeUgRbTtkdSsZo2o', false)
            ->assertSee('Leave a review')
            ->assertSee('images/fht-logo.png', false)
            ->assertSee('Federation of Holistic Therapists logo');
    }

    public function test_an_appointment_request_can_be_stored(): void
    {
        $this->post('/appointment-request', [
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
            'message' => 'I would like to ask about an appointment.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('appointment_requests', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_appointment_request_requires_valid_fields(): void
    {
        $this->post('/appointment-request', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ])->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
