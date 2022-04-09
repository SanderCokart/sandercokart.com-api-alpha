<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;

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

    public function markEmailAsVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification(?string $token = null): void
    {
        $this->notify(new VerifyEmailNotification());
    }
}
