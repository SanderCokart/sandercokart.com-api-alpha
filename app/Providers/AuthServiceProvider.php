<?php

namespace App\Providers;

use App\Notifications\EmailChange;
use App\Notifications\PasswordChange;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
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

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return config('app.url') .
                URL::temporarySignedRoute('verification.verify',
                    now()->addMinutes(config('auth.verification.expire', 60)),
                    ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification()), 'type' => 'verify'],
                    false);
        });

        PasswordChange::createUrlUsing(function ($notifiable, $token) {
            return config('app.url') . '/password/compromised?' . Arr::query(['user' => $notifiable->getKey(), 'token' => $token]);
        });

        EmailChange::createUrlUsing(function ($notifiable, $token) {
            return config('app.url') . '/email/compromised?' . Arr::query(['user' => $notifiable->getKey(), 'token' => $token]);
        });
    }
}
