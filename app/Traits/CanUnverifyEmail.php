<?php

namespace App\Traits;

trait CanUnverifyEmail
{
    public function unmarkEmailAsVerified(): void
    {
        $this->forceFill(['email_verified_at' => null])->save();
    }
}
