<?php

namespace App\Contracts;

interface CanChangePasswordContract
{
    /**
     * Change the user's password
     * Stores token in the database. The token is used to verify the user's email.
     * The old email is notified that the password has changed in case it has been compromised.
     *
     * @param string $newPassword
     * @return void
     */
    public function changePasswordAndNotify(string $newPassword);
}
