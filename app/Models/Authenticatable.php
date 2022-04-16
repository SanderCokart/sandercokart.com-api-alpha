<?php

namespace App\Models;

use App\Contracts\CanChangeEmailContract;
use App\Contracts\CanChangePasswordContract;
use App\Contracts\CanResetEmailContract;
use App\Contracts\CanResetPasswordContract;
use App\Contracts\MustVerifyEmailContract;
use App\Traits\CanChangeEmail;
use App\Traits\CanChangePassword;
use App\Traits\CanResetEmail;
use App\Traits\CanResetPassword;
use App\Traits\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class Authenticatable extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    CanChangePasswordContract,
    CanChangeEmailContract,
    CanResetEmailContract,
    MustVerifyEmailContract
{
    use Authorizable,
        MustVerifyEmail,
        CanResetEmail,
        CanChangeEmail,
        CanResetPassword,
        CanChangePassword,
        Notifiable,
        \Illuminate\Auth\Authenticatable;
}
