<?php

namespace Tests\Feature;

use Tests\TestCase;

class TreatmentPagesTest extends TestCase
{
    public function test_each_treatment_has_its_own_detail_page(): void
    {
        foreach (config('treatments') as $slug => $treatment) {
            $response = $this->get(route('treatments.show', $slug))
                ->assertOk()
                ->assertSee($treatment['title'])
                ->assertSee($treatment['summary'])
                ->assertSee('<meta name="title" content="'.$treatment['title'].' in Harrogate | Angel Touch Therapy">', false)
                ->assertSee('<meta name="description" content="'.$treatment['summary'].' Angel Touch Therapy in Harrogate with qualified and fully insured massage therapist Villy.">', false)
                ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
                ->assertSee('<meta name="twitter:title" content="'.$treatment['title'].' in Harrogate | Angel Touch Therapy">', false)
                ->assertSee('<meta name="twitter:image" content="'.asset($treatment['image']).'">', false)
                ->assertSee('<meta property="og:url" content="https://angeltouchtherapy.co.uk/treatments/'.$slug.'">', false)
                ->assertSee('<link rel="canonical" href="https://angeltouchtherapy.co.uk/treatments/'.$slug.'">', false)
                ->assertSee('"url": "https://angeltouchtherapy.co.uk/treatments/'.$slug.'"', false)
                ->assertSee('"@type": "Service"', false)
                ->assertSee('<a class="nav-booking-link" href="'.route('home').'#booking">', false)
                ->assertSee('data-cookie-consent', false)
                ->assertSee('href="'.route('privacy-policy').'"', false)
                ->assertSee('href="'.route('terms-and-conditions').'"', false)
                ->assertSee('data-google-reviews-carousel', false)
                ->assertSee('What clients say on Google.')
                ->assertSee('<span class="google-reviews__position" aria-live="polite" aria-atomic="true">Reviews <b data-google-reviews-current>1–3</b> of 3</span>', false)
                ->assertSee(config('google-reviews.reviews_url'), false)
                ->assertSee('Leave a review')
                ->assertSee('60 minutes')
                ->assertSee('£75')
                ->assertSee('90 minutes')
                ->assertSee('£110')
                ->assertSee('3 × 60-minute sessions')
                ->assertSee('£210');

            $reviewKeys = config("google-reviews.review_groups.{$slug}");

            $this->assertCount(3, $reviewKeys);

            foreach ($reviewKeys as $reviewKey) {
                $response->assertSee(config("google-reviews.reviews.{$reviewKey}.author"));
            }
        }
    }

    public function test_pages_use_distinct_review_groups(): void
    {
        $reviewKeys = collect(config('google-reviews.review_groups'))
            ->flatten();

        $this->assertCount(18, $reviewKeys);
        $this->assertCount(18, $reviewKeys->unique());
    }

    public function test_unknown_treatment_returns_not_found(): void
    {
        $this->get('/treatments/not-a-treatment')->assertNotFound();
    }

    public function test_home_page_links_to_every_treatment(): void
    {
        $response = $this->get('/')->assertOk();

        foreach (array_keys(config('treatments')) as $slug) {
            $response->assertSee(
                'href="'.route('treatments.show', $slug).'" target="_blank" rel="noopener noreferrer"',
                false,
            );
        }
    }

    public function test_sitemap_lists_home_and_every_treatment_page(): void
    {
        $response = $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');

        $response->assertSee('<loc>https://angeltouchtherapy.co.uk/</loc>', false);
        $response->assertSee('<loc>https://angeltouchtherapy.co.uk/privacy-policy</loc>', false);
        $response->assertSee('<loc>https://angeltouchtherapy.co.uk/terms-and-conditions</loc>', false);

        foreach (array_keys(config('treatments')) as $slug) {
            $response->assertSee('<loc>https://angeltouchtherapy.co.uk/treatments/'.$slug.'</loc>', false);
        }
    }
}
