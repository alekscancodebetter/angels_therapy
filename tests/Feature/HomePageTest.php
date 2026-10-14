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
            ->assertSee('images/angel-touch-mark.png', false)
            ->assertSee('images/sports-massage.png', false)
            ->assertSee('images/deep-tissue-massage.png', false)
            ->assertSee('images/swedish-massage.png', false)
            ->assertSee('images/full-body-massage.png', false);
    }

    public function test_testimonials_appear_before_villys_note_and_fht_logo_is_shown(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'I have been seeing Villy for various treatments since 2011.',
                'I am extremely grateful and passionate about my work.',
            ])
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
