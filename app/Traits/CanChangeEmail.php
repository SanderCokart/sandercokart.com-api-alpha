<?php


namespace App\Traits;

use App\Notifications\EmailChangedNotification;
use App\Notifications\PasswordChangedNotification;

trait CanChangeEmail
{
    use HasTokenSecurity;

    public function changeEmailAndNotify(string $newEmail): void
    {
        $this->forceFill([
            'email'             => $newEmail,
            'email_verified_at' => null,
        ])->save();

        $this->notify(new EmailChangedNotification());
    }
}
