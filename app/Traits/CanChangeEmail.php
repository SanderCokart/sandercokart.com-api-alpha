<?php


namespace App\Traits;

use App\Notifications\EmailChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CanChangeEmail
{
    use HasTokenSecurity;

    public function changeEmailAndNotify(string $newEmail): void
    {
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        DB::table('email_changes')->insert([
            'created_at' => now(),
            'expire_at'  => now()->addYear(),
            'token'      => $token,
            'user_id'    => $this->getKey(),
        ]);

        $this->sendEmailChangeNotification($token);

        $this->forceFill([
            'email'             => $newEmail,
            'email_verified_at' => null,
        ])->save();

        $this->sendEmailVerificationNotification();
    }

    public function sendEmailChangeNotification(): void
    {
//        $this->notify(new EmailChange());
    }

}
