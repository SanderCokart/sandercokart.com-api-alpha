<?php

namespace App\Notifications;

use App\Models\User;
use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChange extends Notification
{

    public static ?Closure $createUrlCallback = null;
    public static ?Closure $toMailCallback = null;
    public string $token;


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
        return $this->buildMailMessage($this->passwordChangeUrl($notifiable));
    }

    public function buildMailMessage(string $url): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Change Notification')
            ->line('You are receiving this email because your password has been changed.')
            ->line('If it wasn\'t you who changed the password, please press the button below.')
            ->action('This was NOT me!', $url)
            ->line('if it was indeed you, no further action is required.');
    }

    public function passwordChangeUrl(User $notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }
        return route('password.compromised', ['user' => $notifiable->getKey(), 'token' => $this->token]);
    }
}
