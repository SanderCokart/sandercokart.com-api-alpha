<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ArticleBanner extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'is_private' => 'boolean',
    ];

    protected $guarded = [];


    //<editor-fold desc="Relationships">
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
    //</editor-fold>

    //<editor-fold desc="Custom methods">
    public function isPublic(): bool
    {
        return $this->article->isPublished();
    }

    public function makePrivate(): bool
    {
        if ($this->article->isPublished()) {
            return Storage::move('/public/' . $this->relative_url, '/private/' . $this->relative_url);
        }
        return false;
    }

    public function makePublic(): bool
    {
        if (! $this->article->isPublished()) {
            return Storage::move('/private/' . $this->relative_url, '/public/' . $this->relative_url);
        }
        return false;
    }
    //</editor-fold>
}

