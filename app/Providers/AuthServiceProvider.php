<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
    public function boot(): void
    {
        $this->registerPolicies();
        Password::defaults(function () {
            return Password::min(8)->symbols()->mixedCase()->numbers();
        });

//        VerifyEmail::createUrlUsing(function ($notifiable) {
//            return config('app.url') .
//                URL::temporarySignedRoute('verification.verify',
//                    now()->addMinutes(config('auth.verification.expire', 60)),
//                    ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification()), 'type' => 'verify'],
//                    false);
//        });
//
//        PasswordChangeNotification::createUrlUsing(function ($notifiable, $token) {
//            return config('app.url') . '/password/compromised?' . Arr::query(['user' => $notifiable->getKey(), 'token' => $token]);
//        });
//
//        EmailChangeNotification::createUrlUsing(function ($notifiable, $token) {
//            return config('app.url') . '/email/compromised?' . Arr::query(['user' => $notifiable->getKey(), 'token' => $token]);
//        });
    }
}
