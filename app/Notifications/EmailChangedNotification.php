<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChangedNotification extends Notification
{
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $url = $notifiable->generateUrlWithIdentifierAndToken('email_changes', 'email_change', 'email.compromised', user: $notifiable);
        return $this->buildMailMessage($url);
    }

    public function buildMailMessage(string $url): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Change Notification')
            ->line('You are receiving this email because your email has been changed.')
            ->line('If it wasn\'t you who changed the email, please press the button below.')
            ->action('This was NOT me!', $url)
            ->line('if it was indeed you, no further action is required.');
    }
}
