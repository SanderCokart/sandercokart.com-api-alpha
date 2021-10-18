<?php


namespace App\Traits;

use App\Notifications\EmailChange;

trait CanChangeEmail
{
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
