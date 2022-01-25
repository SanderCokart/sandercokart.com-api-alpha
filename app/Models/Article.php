<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    protected $with = ['status', 'user', 'banner'];
    protected $fillable = ['title', 'excerpt', 'markdown'];

    use HasFactory, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function articleType(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function banner(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable', 'fileable_type', 'fileable_id');
    }

    public function scopePosts($query)
    {
        return $query->where('article_type_id', ArticleType::POST);
    }

    public function scopeTips($query)
    {
        return $query->where('article_type_id', ArticleType::TIP);
    }

    public function scopeCourses($query)
    {
        return $query->where('article_type_id', ArticleType::COURSE);
    }

    public function scopeThoughts($query)
    {
        return $query->where('article_type_id', ArticleType::THOUGHT);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('published', false);
    }

    public function getStatusAttribute()
    {
        return $this->status()->first();
    }

    public function status(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }

}
