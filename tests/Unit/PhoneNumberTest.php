<?php

namespace Tests\Unit;

use App\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PhoneNumberTest extends TestCase
{
    #[DataProvider('validPhoneNumbers')]
    public function test_accepts_valid_national_phone_numbers(string $countryCode, string $phoneNumber): void
    {
        $this->assertTrue(PhoneNumber::isValid($countryCode, $phoneNumber));
    }

    #[DataProvider('invalidPhoneNumbers')]
    public function test_rejects_phone_numbers_that_do_not_match_the_country(string $countryCode, string $phoneNumber): void
    {
        $this->assertFalse(PhoneNumber::isValid($countryCode, $phoneNumber));
    }

    public function test_normalizes_phone_number_to_international_format(): void
    {
        $this->assertSame('+44 7911123456', PhoneNumber::normalize('GB', '07911 123456'));
        $this->assertSame('+359 881234567', PhoneNumber::normalize('BG', '+359 88 123 4567'));
    }

    /** @return array<string, array{string, string}> */
    public static function validPhoneNumbers(): array
    {
        return [
            'UK local mobile' => ['GB', '07911 123456'],
            'UK international mobile' => ['GB', '+44 7911 123456'],
            'German variable length' => ['DE', '030 123456'],
            'Italian significant leading zero' => ['IT', '06 6982'],
            'Bulgarian mobile' => ['BG', '088 123 4567'],
            'United States' => ['US', '(212) 555-0123'],
        ];
    }

    /** @return array<string, array{string, string}> */
    public static function invalidPhoneNumbers(): array
    {
        return [
            'UK too short' => ['GB', '07123'],
            'France too long' => ['FR', '06 12 34 56 789'],
            'letters are not accepted' => ['GB', '07911 CALLME'],
            'wrong international prefix' => ['GB', '+359 88 123 4567'],
        ];
    }
}
