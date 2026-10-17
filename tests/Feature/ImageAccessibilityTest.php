<?php

namespace Tests\Feature;

use Tests\TestCase;

class ImageAccessibilityTest extends TestCase
{
    public function test_public_page_images_have_non_empty_alt_text(): void
    {
        $urls = [
            route('home'),
            route('privacy-policy'),
            route('terms-and-conditions'),
            '/this-page-does-not-exist',
        ];

        foreach (array_keys(config('treatments')) as $slug) {
            $urls[] = route('treatments.show', $slug);
        }

        foreach ($urls as $url) {
            $response = $this->get($url);
            preg_match_all('/<img\b[^>]*>/i', $response->getContent(), $imageTags);

            foreach ($imageTags[0] as $imageTag) {
                $this->assertMatchesRegularExpression(
                    '/\salt=("[^"]+"|\'[^\']+\')/i',
                    $imageTag,
                    "Image without descriptive alt text on {$url}: {$imageTag}",
                );
            }
        }
    }

    public function test_key_treatment_images_have_descriptive_alt_text(): void
    {
        $response = $this->get(route('home'))->assertOk();

        $response->assertSee(
            'alt="Warm massage treatment room at Angel Touch Therapy in Harrogate"',
            false,
        );
        $response->assertSee('alt="Angel Touch Therapy logo"', false);
        $response->assertSee('alt="Patrick Altoft video review thumbnail"', false);

        foreach (config('treatments') as $treatment) {
            $response->assertSee(
                'alt="'.$treatment['title'].' treatment at Angel Touch Therapy in Harrogate"',
                false,
            );
        }
    }
}
