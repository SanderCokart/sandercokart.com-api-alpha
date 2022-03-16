<?php


namespace App\Traits;

use App\Notifications\EmailChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait CanChangeEmail
{
    /**
     * Change the user's email.
     * Stores token in the database. The token is used to verify the user's email.
     * The old email is notified that the email has changed in case it has been compromised.
     *
     * @param string $newEmail
     * @return void
     */
    public function changeEmailAndNotify(string $newEmail)
    {
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        DB::table('email_changes')->insert([
            'created_at' => now(),
            'expire_at' => now()->addYear(),
            'token' => $token,
            'user_id' => $this->getKey(),
        ]);

        $oldEmail = $this->email;
        Notification::route('mail', $oldEmail)->notify(new EmailChange($token));

        $this->forceFill([
            'email' => $newEmail,
            'email_verified_at' => null,
        ])->save();

        $this->sendEmailVerificationNotification();
    }
}
