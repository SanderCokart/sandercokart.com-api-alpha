<?php

namespace App\Traits;

use App\Models\EmailCompromised;
use App\Models\EmailVerification;
use App\Models\PasswordReset;

trait HasTokenExpireableTokens
{
    public function tokenIsExpired(PasswordReset|EmailCompromised|EmailVerification $emailVerification): bool
    {
        return $emailVerification->expires_at < now();
    }
}
