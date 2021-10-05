<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

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

//        VerifyEmail::toMailUsing(function ($notifiable, $url) {
//
//            $parsedUrl = parse_url($url);
//            $path = $parsedUrl['path'];//account/email/verify/1/a4196d4208008a0b86787cb3fbfd86107545f738
//            parse_str($parsedUrl['query'], $query);//expires=1633448562&signature=46c8310049b6439157b56597d0cd90fa7e60d6e6d8809db41d96f4e2337dd397
//
//            $pathArray = explode($path, '/')[5];
//            $user = $pathArray[4];
//            $hash = $pathArray[5];
//            $expires = $query['expires'];
//            $signature = $query['signature'];
//
//            $newUrl = config('spa.url', 'http://localhost:3000') . '/login/?hash=' . $hash . '&' . $query . '&type=verify_email&user=' . $notifiable->id;
//
//            return (new MailMessage)
//                ->subject('Verify Your Email Address!')
//                ->greeting('Hello ' . $notifiable->name . '!')
//                ->line('Click the button below to verify your email address . ')
//                ->action('Verify Email Address', $newUrl)
//                ->salutation(new HtmlString('Kind regards,<br> Sander Cokart'));
//        });

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('SPA_URL') . '/account/password/reset?token=' . $token . '&email=' . $user->email;
        });
    }
}
