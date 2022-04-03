<?php

namespace App\Contracts;

interface MustVerifyEmailContract
{
    public function sendEmailVerificationNotification(?string $token): void;

    public function generateVerificationUrl(?string $identifier, ?string $token): string;

    public function generateEmailVerificationIdentifier(): string;

    public function generateEmailVerificationToken(): string;

    public function insertVerificationTokenIntoDatabase(string $identifier, string $token): void;

    public function hasVerifiedEmail(): bool;

    public function markEmailAsVerified(): void;
}
