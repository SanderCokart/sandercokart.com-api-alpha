<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $url = $notifiable->generateUrlWithIdentifierAndToken('password_changes', 'password_change', 'password.change', user: $notifiable);
        return $this->buildMailMessage($url, $notifiable);
    }

    public function buildMailMessage(string $url, User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Change Notification')
            ->line('You are receiving this email because your password has been changed.')
            ->line('If it wasn\'t you who changed the password, please press the button below.')
            ->action('This was NOT me!', $url)
            ->line('if it was indeed you, no further action is required.');
    }
}
