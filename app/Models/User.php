<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements AuditableContract
{
    use HasFactory, HasApiTokens, Auditable;

    protected $fillable = ['name', 'email', 'password'];

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

    //<editor-fold desc="Relationships">

    protected function email(): Attribute
    {
        return new Attribute(
            set: fn($value) => strtolower($value),
        );
    }

    public function isAdmin(): bool
    {
        return $this->roles->contains(Role::ADMIN);
    }

    //</editor-fold>

    //<editor-fold desc="Attributes">

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    //</editor-fold>
}
