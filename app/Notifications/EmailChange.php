<?php

namespace App\Notifications;

use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EmailChange extends Notification
{

    use Queueable;

    /**
     * The callback that should be used to create the verify email URL.
     *
     * @var Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var Closure|null
     */
    public static $toMailCallback;

    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {

        $this->token = $token;
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
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $emailChangeUrl = $this->changeEmailUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $emailChangeUrl);
        }

        return $this->buildMailMessage($emailChangeUrl, $notifiable);
    }

    /**
     * Get the change email notification mail message for the given URL.
     *
     * @param $url
     * @param mixed $notifiable
     * @return MailMessage
     */
    protected function buildMailMessage($url, $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Change Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You or someone else requested for your email to be altered. If it was you, please click the button below, if not please ignore this email.')
            ->action('Change Email Address', $url)
            ->salutation(new HtmlString('Kind regards,<br> Sander Cokart'));
    }

    /**
     * Get the change email URL for the given notifiable
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function changeEmailUrl($notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return url(route('email.change', [
            'id' => $notifiable->getKey(),
            'token' => $this->token,
            'hash' => sha1($notifiable->getEmailForEmailChange()),
        ], false));
    }

    /**
     * Set a callback that should be used when creating the change email URL.
     *
     * @param Closure $callback
     * @return void
     */
    public static function createUrlUsing(Closure $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param Closure $callback
     * @return void
     */
    public static function toMailUsing(Closure $callback): void
    {
        static::$toMailCallback = $callback;
    }
}
