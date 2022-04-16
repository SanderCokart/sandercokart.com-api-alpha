<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['identifier', 'token', 'expires_at'];
}
