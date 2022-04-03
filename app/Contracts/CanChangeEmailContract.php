<?php

namespace App\Contracts;

interface CanChangeEmailContract
{
    /**
     * Change the user's email.
     * Stores token in the database. The token is used to verify the user's email.
     * The old email is notified that the email has changed in case it has been compromised.
     *
     * @param string $newEmail
     * @return void
     */
    public function changeEmailAndNotify(string $newEmail);
}
