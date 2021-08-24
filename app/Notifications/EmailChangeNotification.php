<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class EmailChangeNotification extends Notification
{
    use Queueable;

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
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Change Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->action('Change Email Address', $this->verifyRoute($notifiable))
            ->salutation(new HtmlString('Kind regards,<br> Sander Cokart'));
    }

    /**
     * @param mixed $notifiable
     * @return string
     */
    private function verifyRoute($notifiable): string
    {
        $url = URL::temporarySignedRoute('email-change', now()->addHour(), [
            'user' => $notifiable,
            'email' => $notifiable->routes['mail'],
        ]);

        dd($url);
        return $url;
    }
}
