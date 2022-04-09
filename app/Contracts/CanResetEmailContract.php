<?php

namespace App\Contracts;

interface CanResetEmailContract
{
    public function resetEmailAndNotify(string $newEmail): void;

    public function sendEmailResetNotification(): void;
}
