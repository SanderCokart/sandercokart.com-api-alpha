<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    use HasFactory;

    const GUEST = 1;
    const ADMIN = 2;
    const VERIFIED = 3;

    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
