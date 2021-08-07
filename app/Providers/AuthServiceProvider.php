<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {

            $data = explode('/', $url)[6];
            $splitData = explode('?', $data);

            $hash = $splitData[0];
            $query = $splitData[1];

            $newUrl = env('SPA_URL', 'http://localhost:3000') . '/login/?hash=' . $hash . '&' . $query . '&type=verify_email&user=' . $notifiable->id;

            return (new MailMessage)
                ->subject('Verify Your Email Address!')
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Click the button below to verify your email address . ')
                ->action('Verify Email Address', $newUrl)
                ->salutation(new HtmlString('Kind regards,<br> Sander Cokart'));
        });
    }
}
