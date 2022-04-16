<?php

namespace App\Traits;

use App\Models\CompromisedEmail;
use App\Models\CompromisedPassword;
use App\Models\EmailVerification;
use App\Models\PasswordReset;

trait HasTokenExpireableTokens
{
    public function tokenIsExpired(PasswordReset|CompromisedEmail|EmailVerification|CompromisedPassword $emailVerification): bool
    {
        return $emailVerification->expires_at < now();
    }
}
