<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    public function test_unknown_url_returns_a_branded_404_page(): void
    {
        $response = $this->get('/this-page-does-not-exist');

        $response
            ->assertNotFound()
            ->assertSee('We couldn’t find that page.')
            ->assertSee('Return home')
            ->assertSee('View treatments')
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee('href="'.route('home').'#booking"', false)
            ->assertSee('<meta name="title" content="Page Not Found | Angel Touch Therapy">', false)
            ->assertSee('<meta name="description" content="The page you requested could not be found on the Angel Touch Therapy website.">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false);
    }
}
