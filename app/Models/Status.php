<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Status extends Model
{
    use HasFactory;

    const UNLISTED = 1;
    const PUBLISHED = 2;
    const ARCHIVED = 3;

    public $timestamps = false;
    protected $touches = ['article'];
    protected $guarded = [];

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'statusable');
    }


}
