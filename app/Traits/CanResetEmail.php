<?php

namespace App\Traits;

trait CanResetEmail
{
    use HasTokenSecurity;

    public function resetEmailAndNotify(string $newEmail): void
    {

    }

    public function sendEmailResetNotification(): void
    {

    }
}
