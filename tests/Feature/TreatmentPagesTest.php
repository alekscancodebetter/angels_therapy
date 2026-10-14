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
            $response->assertSee(route('treatments.show', $slug), false);
        }
    }
}
