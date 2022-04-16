<?php

namespace App\Contracts;

interface MustVerifyEmailContract
{
    public function hasVerifiedEmail(): bool;

    public function markEmailAsVerified(): bool;

    public function sendEmailVerificationNotification(): void;
}
