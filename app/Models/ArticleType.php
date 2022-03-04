<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleType extends Model
{
    use HasFactory;

    const POST = 1;
    const TIP = 2;
    const COURSE = 3;

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'article_type_id');
    }
}
