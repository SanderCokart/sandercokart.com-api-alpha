<?php

namespace App\Contracts;

interface CanResetPasswordContract
{
    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification(): void;
}
