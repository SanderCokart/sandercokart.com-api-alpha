<?php

namespace App\Models;

use App\Contracts\CanChangeEmail as CanChangeEmailContract;
use App\Traits\CanChangeEmail;
use App\Traits\CanChangePassword;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements MustVerifyEmailContract, CanResetPasswordContract, CanChangeEmailContract, AuditableContract
{
    use HasFactory, Notifiable, MustVerifyEmail, CanChangePassword, CanResetPassword, HasApiTokens, CanChangeEmail, Auditable, HasApiTokens, SoftDeletes;

    protected $with = ['roles'];

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
