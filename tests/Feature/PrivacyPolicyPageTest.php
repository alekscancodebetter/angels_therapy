<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyPageTest extends TestCase
{
    public function test_privacy_policy_page_explains_how_personal_information_is_handled(): void
    {
        $this->get(route('privacy-policy'))
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('18 October 2026')
            ->assertSee('Ms Violina Todorova Spasova')
            ->assertSee('Data protection contact')
            ->assertSee('How and why we use your information')
            ->assertSee('Your data protection rights')
            ->assertSee('angel-touch-cookie-consent-v3')
            ->assertSee('Meta Pixel')
            ->assertSee('https://ico.org.uk/make-a-complaint/', false)
            ->assertSee('<meta name="title" content="Privacy Policy | Angel Touch Therapy">', false)
            ->assertSee('<meta name="description" content="Privacy policy for Angel Touch Therapy in Harrogate, including how personal information is collected, used, stored and protected.">', false)
            ->assertSee('<meta name="twitter:title" content="Privacy Policy | Angel Touch Therapy">', false)
            ->assertSee('<meta name="twitter:description" content="How Angel Touch Therapy collects, uses, stores and protects personal information.">', false)
            ->assertSee('<meta property="og:url" content="https://angeltouchtherapy.co.uk/privacy-policy">', false)
            ->assertSee('<link rel="canonical" href="https://angeltouchtherapy.co.uk/privacy-policy">', false);
    }
}
