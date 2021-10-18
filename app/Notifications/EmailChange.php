<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChange extends Notification
{
    use Queueable;

    private $token;
    private $userId;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @param int $user
     */
    public function __construct(string $token, int $user)
    {
        $this->token = $token;
        $this->userId = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        $url = config('app.url') . route('email.compromised', [
                'user' => $this->userId,
                'token' => $this->token,
            ], false);

        return (new MailMessage)
            ->subject('Email Change Notification')
            ->line('You are receiving this email because your email has been changed.')
            ->line('If it wasn\'t you who changed the email, please press the button below.')
            ->action('This was NOT me!', $url)
            ->line('if it was indeed you, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            //
        ];
    }
}
