<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    public function test_mail_configuration_exposes_required_contract_keys(): void
    {
        $this->assertArrayHasKey('default', config('mail'));
        $this->assertArrayHasKey('smtp', config('mail.mailers'));
        $this->assertArrayHasKey('timeout', config('mail.mailers.smtp'));
        $this->assertArrayHasKey('from', config('mail'));
        $this->assertArrayHasKey('reply_to', config('mail'));
        $this->assertArrayHasKey('address', config('mail.reply_to'));
        $example = file_get_contents(base_path('.env.example'));

        $this->assertStringContainsString('QUEUE_CONNECTION=database', $example);
    }

    public function test_example_environment_contains_no_real_mail_credentials(): void
    {
        $example = file_get_contents(base_path('.env.example'));

        $this->assertStringContainsString('MAIL_TIMEOUT=', $example);
        $this->assertStringContainsString('MAIL_REPLY_TO_ADDRESS=null', $example);
        $this->assertStringNotContainsString('smtp://', $example);
        $this->assertDoesNotMatchRegularExpression('/MAIL_PASSWORD=(?!null\\s*$).+/m', $example);
    }
}
