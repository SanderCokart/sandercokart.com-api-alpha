<?php


namespace App\Traits;

use App\Notifications\PasswordChange;

trait CanChangePassword
{
    /**
     * Send the change email notification
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordChangeNotification(string $token): void
    {
        $this->notify(new PasswordChange($token));
    }
}
