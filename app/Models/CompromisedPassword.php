<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompromisedPassword extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'identifier';
    protected $keyType = 'string';
    protected $table = 'password_changes';
    protected $fillable = ['user_id', 'identifier', 'token', 'expires_at'];

    use HasFactory;
}
