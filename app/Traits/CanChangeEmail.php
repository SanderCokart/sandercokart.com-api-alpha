<?php


namespace App\Traits;

trait CanChangeEmail
{
    /**
     * Unmark the given user's email as verified
     *
     * @return bool
     */
    public function markEmailAsUnverified(): bool
    {
        $this->email_verified_at = null;
        return $this->save();
    }
}
