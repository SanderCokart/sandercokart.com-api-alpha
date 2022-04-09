<?php


namespace App\Traits;

use App\Notifications\EmailChange;
use App\Notifications\PasswordChange;
use DB;
use Illuminate\Support\Str;

trait CanChangePassword
{
    use HasTokenSecurity;

    /**
     * Send the change email notification
     *
     * @param string $newPassword
     * @return void
     */
    public function changePasswordAndNotify(string $newPassword): void
    {
        $this->forceFill(['password' => bcrypt($newPassword)])->save();

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));
        DB::table('password_changes')->insert([
            'created_at' => now(),
            'expire_at' => now()->addYear(),
            'token' => $token,
            'user_id' => $this->getKey(),
        ]);

        $this->notify(new PasswordChange($token));
    }
}
