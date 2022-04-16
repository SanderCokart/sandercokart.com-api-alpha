<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'identifier';
    protected $keyType = 'string';
    protected $table = 'password_resets';
    protected $fillable = ['user_id', 'identifier', 'token', 'expires_at'];
}
