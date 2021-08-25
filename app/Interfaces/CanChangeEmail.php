<?php


namespace App\Interfaces;


interface CanChangeEmail
{
    /**
     * Send the change email notification
     *
     * @param string $token
     * @return void
     */
    public function sendEmailChangeNotification(string $token): void;

    /**
     * Get the original email address where the request of email change should be sent to
     *
     * @return string
     */
    public function getEmailForChange(): string;

    /**
     * Unmark the given user's email as verified
     *
     * @return bool
     */
    public function markEmailAsUnverified(): bool;
}
