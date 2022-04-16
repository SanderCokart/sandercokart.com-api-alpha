<?php

namespace App\Traits;

use App\Notifications\ForgotPasswordNotification;

trait CanResetPassword
{
    use HasTokenSecurity;

    public function sendPasswordResetNotification(): void
    {
        $this->notify(new ForgotPasswordNotification());
    }
}
