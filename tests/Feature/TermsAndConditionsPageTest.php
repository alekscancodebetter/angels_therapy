<?php

namespace Tests\Feature;

use Tests\TestCase;

class TermsAndConditionsPageTest extends TestCase
{
    public function test_terms_page_explains_the_booking_and_treatment_contract(): void
    {
        $this->get(route('terms-and-conditions'))
            ->assertOk()
            ->assertSee('Terms &amp; Conditions', false)
            ->assertSee('16 October 2026')
            ->assertSee('Bookings and confirmation')
            ->assertSee('Changes and cancellations')
            ->assertSee('Your consumer rights')
            ->assertSee('reasonable care and skill')
            ->assertSee('60 minutes:</strong> £75', false)
            ->assertSee('90 minutes:</strong> £110', false)
            ->assertSee('3 × 60-minute sessions:</strong> £210', false)
            ->assertSee('href="'.route('privacy-policy').'"', false)
            ->assertSee('<meta name="title" content="Terms &amp; Conditions | Angel Touch Therapy">', false)
            ->assertSee('<meta name="description" content="Terms and conditions for massage therapy appointments, bookings, payments and cancellations with Angel Touch Therapy in Harrogate.">', false)
            ->assertSee('<meta name="twitter:title" content="Terms &amp; Conditions | Angel Touch Therapy">', false)
            ->assertSee('<meta name="twitter:description" content="The terms that apply when you book and receive a massage treatment from Angel Touch Therapy.">', false)
            ->assertSee('<meta property="og:url" content="https://angeltouchtherapy.co.uk/terms-and-conditions">', false)
            ->assertSee('<link rel="canonical" href="https://angeltouchtherapy.co.uk/terms-and-conditions">', false);
    }
}
