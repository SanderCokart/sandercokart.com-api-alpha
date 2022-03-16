<?php

namespace App\Models;

use App\Contracts\CanChangeEmail as CanChangeEmailContract;
use App\Contracts\CanChangePassword as CanChangePasswordContract;
use App\Contracts\CanResetEmail as CanResetEmailContract;
use App\Contracts\CanResetPassword as CanResetPasswordContract;
use App\Traits\CanChangeEmail;
use App\Traits\CanChangePassword;
use App\Traits\CanResetEmail;
use App\Traits\CanResetPassword;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
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
        CanChangeEmail,
        CanResetPassword,
        CanResetEmail,
        CanChangePassword,
        MustVerifyEmail,
        Notifiable,
        \Illuminate\Auth\Authenticatable;
}
