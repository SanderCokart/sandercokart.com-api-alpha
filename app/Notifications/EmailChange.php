<?php

namespace App\Notifications;

use App\Models\User;
use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChange extends Notification
{
    public static ?Closure $createUrlCallback = null;
    public static ?Closure $toMailCallback = null;
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public static function createUrlUsing(Closure $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    public static function toMailUsing(Closure $callback): void
    {
        static::$toMailCallback = $callback;
    }

    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        return $this->buildMailMessage($this->emailChangeUrl($notifiable));
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

    public function emailChangeUrl(User $notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return route('email.reset', ['token' => $this->token, 'user' => $notifiable->getKey()]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param User $notifiable
     * @return array
     */
    public function toArray(User $notifiable): array
    {
        return [

        ];
    }
}
