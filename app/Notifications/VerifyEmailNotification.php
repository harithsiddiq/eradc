<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Throwable;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        $message = (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('emails.verify-email', [
                'url' => $verificationUrl,
                'user' => $notifiable,
            ]);

        if ($address = config('mail.reply_to.address')) {
            $message->replyTo($address, config('mail.reply_to.name'));
        }

        return $message;
    }

    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Email verification notification failed.', [
            'exception' => $exception::class,
        ]);
    }
}
