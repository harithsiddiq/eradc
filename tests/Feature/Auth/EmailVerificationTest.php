<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_verification_activates_user(): void
    {
        $user = User::factory()->unverified()->create(['is_active' => false]);

        $response = $this->actingAs($user)->get($this->verificationUrl($user));

        $response->assertRedirect(route('home'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_invalid_and_expired_verification_links_are_rejected(): void
    {
        $user = User::factory()->unverified()->create(['is_active' => false]);

        $this->actingAs($user)->get($this->verificationUrl($user).'&invalid=1')->assertForbidden();

        $expired = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinute(),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())],
        );

        $this->actingAs($user)->get($expired)->assertForbidden();
        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_unverified_user_can_resend_and_verified_user_is_redirected(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->post(route('verification.send'))
            ->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);

        $user->markEmailAsVerified();
        $this->actingAs($user)->post(route('verification.send'))->assertRedirect(route('home'));

        Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);
    }

    public function test_resend_is_throttled_after_six_attempts_per_minute(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        foreach (range(1, 6) as $attempt) {
            $this->actingAs($user)->post(route('verification.send'))->assertRedirect();
        }

        $this->actingAs($user)->post(route('verification.send'))->assertTooManyRequests();
        Notification::assertSentToTimes($user, VerifyEmailNotification::class, 6);
    }

    private function verificationUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())],
        );
    }
}
