<?php

namespace Tests\Feature;

use Tests\TestCase;

class TreatmentPagesTest extends TestCase
{
    public function test_each_treatment_has_its_own_detail_page(): void
    {
        foreach (config('treatments') as $slug => $treatment) {
            $this->get(route('treatments.show', $slug))
                ->assertOk()
                ->assertSee($treatment['title'])
                ->assertSee($treatment['summary'])
                ->assertSee('<link rel="canonical" href="'.route('treatments.show', $slug).'">', false)
                ->assertSee('"@type": "Service"', false)
                ->assertSee('<a class="nav-booking-link" href="'.route('home').'#booking">', false)
                ->assertSee('data-cookie-consent', false)
                ->assertSee('data-google-reviews-carousel', false)
                ->assertSee('What clients say on Google.')
                ->assertSee('Ryan Gordon')
                ->assertSee('Nik Vjestica')
                ->assertSee('Leave a review')
                ->assertSee('To be confirmed');
        }
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

        $response->assertSee('<loc>'.route('home').'</loc>', false);

        foreach (array_keys(config('treatments')) as $slug) {
            $response->assertSee('<loc>'.route('treatments.show', $slug).'</loc>', false);
        }
    }
}
