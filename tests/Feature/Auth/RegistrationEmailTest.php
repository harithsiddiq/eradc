<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use RuntimeException;
use Tests\TestCase;

class RegistrationEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }

    public function test_successful_registration_queues_exactly_one_verification_notification(): void
    {
        Notification::fake();

        $response = $this->post('/register', $this->validRegistrationData());

        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'new-user@example.com')->firstOrFail();

        $this->assertFalse($user->is_active);
        Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);
    }

    public function test_invalid_and_duplicate_registrations_do_not_queue_notifications(): void
    {
        Notification::fake();

        User::factory()->create(['email' => 'existing@example.com']);

        $this->post('/register', array_merge($this->validRegistrationData(), [
            'email' => 'not-an-email',
        ]))->assertSessionHasErrors('email');

        $this->post('/register', array_merge($this->validRegistrationData(), [
            'email' => 'existing@example.com',
        ]))->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    public function test_notification_uses_configured_subject_reply_to_and_application_url(): void
    {
        config([
            'app.url' => 'https://production.example.com',
            'mail.reply_to.address' => 'support@example.com',
            'mail.reply_to.name' => 'Support',
        ]);
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme('https');

        $user = User::factory()->unverified()->create();
        $mail = (new VerifyEmailNotification)->toMail($user);

        $this->assertSame('Verify Your Email Address', $mail->subject);
        $this->assertSame('support@example.com', $mail->replyTo[0][0]);
        $this->assertStringStartsWith('https://production.example.com/email/verify/', $mail->viewData['url']);
    }

    public function test_notification_failure_logs_only_sanitized_context(): void
    {
        Log::spy();

        (new VerifyEmailNotification)->failed(new RuntimeException('smtp://user:secret@example.com'));

        Log::shouldHaveReceived('error')->once()->with(
            'Email verification notification failed.',
            [
                'exception' => RuntimeException::class,
            ],
        );
    }

    private function validRegistrationData(): array
    {
        return [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'level' => 'fresh',
        ];
    }
}
