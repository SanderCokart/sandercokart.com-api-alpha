<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleType extends Model
{
    use HasFactory;

    const POSTS = ['id' => 1, 'name' => 'posts'];
    const TIPS_AND_TUTORIALS = ['id' => 2, 'name' => 'tips-&-tutorials'];
    const COURSES = ['id' => 3, 'name' => 'courses'];

    public $timestamps = false;
    protected $fillable = ['name'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'article_type_id');
    }
}
