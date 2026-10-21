<?php

namespace Tests\Feature;

use App\Mail\AppointmentRequestReceivedBusiness;
use App\Mail\AppointmentRequestReceivedCustomer;
use App\Models\AppointmentRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentRequestNotificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_enquiry_queues_customer_and_business_emails(): void
    {
        Mail::fake();
        config()->set('mail.business_recipients', [
            'contact@angeltouchtherapy.co.uk',
            'owner@example.com',
        ]);

        $this->from(route('home'))->post(route('appointment-request.store'), [
            'name' => 'Alex Visitor',
            'email' => 'alex@example.com',
            'message' => 'Could you recommend a treatment?',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('status');

        $appointmentRequest = AppointmentRequest::query()->firstOrFail();

        $this->assertSame('Could you recommend a treatment?', $appointmentRequest->message);
        Mail::assertQueued(
            AppointmentRequestReceivedCustomer::class,
            fn (AppointmentRequestReceivedCustomer $mail): bool => $mail->hasTo('alex@example.com'),
        );
        Mail::assertQueued(
            AppointmentRequestReceivedBusiness::class,
            fn (AppointmentRequestReceivedBusiness $mail): bool => $mail->hasTo('contact@angeltouchtherapy.co.uk'),
        );
        Mail::assertQueued(
            AppointmentRequestReceivedBusiness::class,
            fn (AppointmentRequestReceivedBusiness $mail): bool => $mail->hasTo('owner@example.com'),
        );
        Mail::assertQueued(AppointmentRequestReceivedBusiness::class, 2);
    }

    public function test_enquiry_business_email_escapes_customer_message(): void
    {
        $appointmentRequest = AppointmentRequest::create([
            'name' => 'Alex Visitor',
            'email' => 'alex@example.com',
            'message' => '<script>alert("enquiry")</script>',
        ]);

        $html = (new AppointmentRequestReceivedBusiness($appointmentRequest))->render();

        $this->assertStringNotContainsString('<script>alert("enquiry")</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
