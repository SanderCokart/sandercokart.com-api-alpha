<?php

namespace App\Traits;

trait CanResetEmail
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForEmailReset(): string
    {
        return $this->email;
    }

    /**
     * Send the email reset notification.
     *
     * @param $token
     * @return void
     */
    public function sendEmailResetNotification($token): void
    {

    }
}
