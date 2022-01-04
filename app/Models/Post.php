<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $with = ['user:id,name', 'banner', 'status'];
    protected $fillable = ['title', 'excerpt', 'markdown'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function banner(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function scopePublished($query)
    {
        return $query->where('status_id', Status::PUBLISHED);
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }
}
