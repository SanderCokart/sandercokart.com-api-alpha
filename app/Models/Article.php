<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin Article
 */
class Article extends Model
{
    private File $banner;

    protected $fillable = [
        'title',
        'excerpt',
        'markdown',
        'article_type_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function banner(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable', 'fileables', 'file_id', 'fileable_id', 'id', 'id');
    }
    //</editor-fold>

    //<editor-fold desc="Scopes">
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeDrafts(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }
    //</editor-fold>

    //<editor-fold desc="Publishing">
    public function isPublished(): bool
    {
        return (bool)$this->published_at;
    }

    public function publish(): void
    {
        $this->published_at = now()->toDateTimeString();
        $this->banner()->first()->makePublic();
        $this->publicizeImagesWithinMarkdown();
    }

    public function unPublish(): void
    {
        $this->published_at = null;
        $this->banner()->first()->makePrivate();
        $this->privatizeImagesWithinMarkdown();
    }

    public function publicizeImagesWithinMarkdown(): void
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


    public function privatizeImagesWithinMarkdown(): void
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

    //<editor-fold desc="Mutators">
    protected function getBannerAttribute(): ?File
    {
        return $this->banner()->first();
    }
    //</editor-fold>

}
