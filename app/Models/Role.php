<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    use HasFactory;

    const USER = 1;
    const ADMIN = 2;

    public $timestamps = false;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
