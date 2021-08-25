<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmailChangeRequest extends Model
{
    protected $table = 'email_changes';
    use HasFactory;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
