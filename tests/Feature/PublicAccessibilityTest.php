<?php

namespace Tests\Feature;

use DOMDocument;
use DOMXPath;
use Illuminate\Testing\TestResponse;
use Illuminate\Testing\TestView;
use Tests\TestCase;

class PublicAccessibilityTest extends TestCase
{
    public function test_public_pages_render_core_accessibility_landmarks(): void
    {
        $urls = [
            route('home'),
            route('privacy-policy'),
            route('terms-and-conditions'),
        ];

        foreach (array_keys(config('treatments')) as $slug) {
            $urls[] = route('treatments.show', $slug);
        }

        foreach ($urls as $url) {
            $this->assertAccessiblePageStructure($this->get($url)->assertOk(), $url);
        }

        $notFoundUrl = '/accessibility-check-missing-page';
        $this->assertAccessiblePageStructure($this->get($notFoundUrl)->assertNotFound(), $notFoundUrl);
    }

    public function test_booking_form_exposes_labels_help_and_validation_errors(): void
    {
        $messages = collect([
            'starts_at',
            'treatment_slug',
            'duration_minutes',
            'name',
            'email',
            'phone',
            'terms_accepted',
        ])->mapWithKeys(fn (string $field): array => [$field => "Please check {$field}."])->all();
        $this->withViewErrors($messages);

        $response = $this->view('home');

        $xpath = $this->xpathFor($response);

        foreach (['treatment_slug', 'duration_minutes', 'name', 'phone', 'email', 'message', 'terms_accepted'] as $fieldId) {
            $this->assertSame(
                1,
                $xpath->query("//label[@for='{$fieldId}']")->length,
                "The {$fieldId} field does not have an explicit label.",
            );
        }

        foreach (['treatment_slug', 'duration_minutes', 'name', 'phone', 'email', 'terms_accepted'] as $fieldId) {
            $this->assertSame(
                1,
                $xpath->query("//*[@id='{$fieldId}' and @aria-invalid='true' and contains(@aria-describedby, '{$fieldId}_error')]")->length,
                "The {$fieldId} validation error is not associated with its field.",
            );
            $this->assertSame(
                1,
                $xpath->query("//*[@id='{$fieldId}_error']")->length,
                "The {$fieldId} validation error does not have a stable identifier.",
            );
        }

        $this->assertSame(1, $xpath->query("//*[@id='message' and contains(@aria-describedby, 'message_help')]")->length);
        $this->assertSame(1, $xpath->query("//*[@id='starts_at_error']")->length);
        $this->assertSame(1, $xpath->query("//*[@data-booking-errors and @role='alert' and @tabindex='-1']")->length);
    }

    public function test_new_tab_links_include_security_relationships(): void
    {
        $response = $this->get(route('home'))->assertOk();
        $xpath = $this->xpathFor($response);
        $newTabLinks = $xpath->query("//a[@target='_blank']");

        $this->assertGreaterThan(0, $newTabLinks->length);

        foreach ($newTabLinks as $link) {
            $relationships = preg_split('/\s+/', trim($link->getAttribute('rel'))) ?: [];

            $this->assertContains('noopener', $relationships);
            $this->assertContains('noreferrer', $relationships);
        }
    }

    private function assertAccessiblePageStructure(TestResponse $response, string $url): void
    {
        $xpath = $this->xpathFor($response);

        $this->assertSame('en', $xpath->query('/html')->item(0)?->getAttribute('lang'), "Missing page language on {$url}.");
        $this->assertSame(1, $xpath->query('//title[normalize-space()]')->length, "Missing page title on {$url}.");
        $this->assertSame(1, $xpath->query("//a[contains(concat(' ', normalize-space(@class), ' '), ' skip-link ') and @href='#main']")->length, "Missing skip link on {$url}.");
        $this->assertSame(1, $xpath->query("//main[@id='main']")->length, "Missing main landmark on {$url}.");
        $this->assertSame(1, $xpath->query('//h1')->length, "The page must have exactly one level-one heading on {$url}.");
        $this->assertSame(1, $xpath->query("//nav[@id='main-nav' and @aria-label]")->length, "Missing named main navigation on {$url}.");
        $this->assertSame(1, $xpath->query("//button[@data-menu-toggle and @aria-controls='main-nav' and @aria-expanded='false']")->length, "The mobile navigation trigger is incomplete on {$url}.");
        $this->assertSame(1, $xpath->query("//*[@role='dialog' and @aria-modal='true' and @aria-labelledby='cookie-consent-title' and @aria-describedby='cookie-consent-description']")->length, "The cookie consent dialog is incomplete on {$url}.");
    }

    private function xpathFor(TestResponse|TestView $response): DOMXPath
    {
        $document = new DOMDocument;
        $previousState = libxml_use_internal_errors(true);
        $content = $response instanceof TestResponse ? $response->getContent() : (string) $response;
        $document->loadHTML($content);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        return new DOMXPath($document);
    }
}
