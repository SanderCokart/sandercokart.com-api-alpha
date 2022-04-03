<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    protected $fillable = [
        'title',
        'excerpt',
        'markdown',
        'article_banner_id',
        'article_type_id',
        'published_at',
    ];

    use HasFactory, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('title')
                          ->saveSlugsTo('slug');
    }


    //<editor-fold desc="Relationships">
    public function articleType(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class);
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(ArticleBanner::class, 'article_banner_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    //</editor-fold>

    //<editor-fold desc="Scopes">\
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeDrafts(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    public function IsPublished(): bool
    {
        return ! ! $this->published_at;
    }

    //</editor-fold>

    //<editor-fold desc="Custom methods">
    public function publish()
    {
        $this->published_at = now();
        $this->banner->makePublic();
        $this->publicizeImagesWithinMarkdown();

    }

    public function publicizeImagesWithinMarkdown()
    {
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
    }

    public function unPublish()
    {
        $this->published_at = null;
        $this->banner->makePublic();
        $this->publicizeImagesWithinMarkdown();
    }

    public function privatizeImagesWithinMarkdown()
    {
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

        $this->fill(['markdown' => $markdown])->save();
    }
    //</editor-fold>
}
