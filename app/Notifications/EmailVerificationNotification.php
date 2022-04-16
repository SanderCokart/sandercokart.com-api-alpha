<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $url = $notifiable->generateUrlWithIdentifierAndToken('email_verifications', 'verify', 'email.verify');
        return $this->buildMessage($url, $notifiable);
    }

    public function buildMessage(string $url, User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $url)
            ->line('Thank you for using our application!');
    }
}

//$notifiable->generateUrlWithIdentifierAndToken('email_verifications', 'verify', 'email.verify')
