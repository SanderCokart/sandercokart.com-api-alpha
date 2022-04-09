<?php

namespace App\Contracts;

interface MustVerifyEmailContract
{
    public function hasVerifiedEmail(): bool;

    public function markEmailAsVerified(): void;

    public function sendEmailVerificationNotification(?string $token): void;
}
