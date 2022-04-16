<?php


namespace App\Traits;

use App\Notifications\EmailChangedNotification;
use App\Notifications\PasswordChangedNotification;
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

        $this->notify(new PasswordChangedNotification());
    }
}
