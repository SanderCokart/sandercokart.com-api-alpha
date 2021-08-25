<?php


namespace App\Traits;

use App\Notifications\EmailChange;

trait CanChangeEmail
{
    /**
     * Send the change email notification
     *
     * @param string $token
     * @return void
     */
    public function sendEmailChangeNotification(string $token): void
    {
        $this->notify(new EmailChange($token));
    }

    /**
     * Get the original email address where the request of email change should be sent to
     *
     * @return string
     */
    public function getEmailForChange(): string
    {
        return $this->email;
    }

    /**
     * Unmark the given user's email as verified
     *
     * @return bool
     */
    public function markEmailAsUnverified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => null,
        ])->save();
    }
}
