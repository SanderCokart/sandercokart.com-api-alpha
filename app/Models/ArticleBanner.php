<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleBanner extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function article(): HasOne
    {
        return $this->hasOne(Article::class);
    }
}
