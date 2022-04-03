<?php

namespace App\Contracts;

interface CanResetEmailContract
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForEmailReset(): string;

    /**
     * Send the email reset notification.
     *
     * @param $token
     * @return void
     */
    public function sendEmailResetNotification($token): void;
}
