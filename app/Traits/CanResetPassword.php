<?php

namespace App\Traits;

use App\Notifications\ResetPasswordNotification;

trait CanResetPassword
{
    public function sendPasswordResetNotification(string $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
