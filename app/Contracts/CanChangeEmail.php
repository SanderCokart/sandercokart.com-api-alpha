<?php

namespace App\Contracts;

interface CanChangeEmail
{
    /**
     * Unmark the given user's email as verified
     *
     * @return bool
     */
    public function markEmailAsUnverified(): bool;
}
