<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\URL;

/**
 * @mixin User
 */
trait MustVerifyEmail
{
    use HasTokenSecurity;

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EmailVerificationNotification());
    }
}
