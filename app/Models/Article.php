<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    protected $fillable = ['title', 'excerpt', 'markdown'];
    protected $with = ['user', 'statuses', 'banner'];

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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function getStatusAttribute()
    {
        return $this->statuses()->first();
    }

    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }

    public function togglePrivacy()
    {
        if ($this->status->id === Status::PUBLISHED) {
            if ($this->published_at === null) {
                $this->published_at = now();
            }
            $this->banner->makePublic();
            $patternToGetUrl = '%\Q' . config('app.local_url') . '/files/\E\d%';
            $patternToGetId = '%\d+$%';

            $markdown = $this->markdown;

            preg_match($patternToGetUrl, $markdown, $urls);

            foreach ($urls as $url) {
                preg_match($patternToGetId, $url, $ids);
                $id = $ids[0];

                $file = File::find($id);
                $file->makePublic();

                $newUrl = config('app.local_url') . '/' . $file->relative_url;
                $markdown = str_replace($url, $newUrl, $markdown);
            }

            $this->fill(['markdown' => $markdown])->save();

        } else {
            $this->banner->makePrivate();
            $patternToGetPublicUrl = '%\Q' . config('app.local_url') . '/uploads/\E.+\.[A-z0-9]+%';
            $patternToGetRelativeUrl = '%\Quploads/\E.+\.[A-z0-9]+%';

            $markdown = $this->markdown;

            preg_match($patternToGetPublicUrl, $markdown, $urls);

            foreach ($urls as $url) {
                preg_match($patternToGetRelativeUrl, $url, $relativeUrls);
                $relativeUrl = $relativeUrls[0];

                $file = File::where('relative_url', $relativeUrl)->firstOrFail();
                $file->makePrivate();

                $newUrl = config('app.local_url') . '/files/' . $file->id;
                $markdown = str_replace($url, $newUrl, $markdown);
            }
        }
    }

}
