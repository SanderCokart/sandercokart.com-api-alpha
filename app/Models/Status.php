<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    const UNLISTED = 1;
    const PUBLISHED = 2;
    const ARCHIVED = 3;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function statusable()
    {
        return $this->morphTo('statusable');
    }

}
