<?php

namespace App\Models;

use App\Events\FileModelDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $guarded = [];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'deleted' => FileModelDeleted::class,
    ];

    public function isPublic(): bool
    {
        return $this->articles()->first()->isPublished();
    }


    //<editor-fold desc="Custom methods">

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'fileable');
    }

    public function makePrivate(): bool
    {
        if ($this->articles()->first()->isPublished()) {
            return Storage::move('/public/' . $this->relative_path, '/private/' . $this->relative_path);
        }
        return false;
    }

    public function makePublic(): bool
    {
        if (! $this->articles()->first()->isPublished()) {
            return Storage::move('/private/' . $this->relative_path, '/public/' . $this->relative_path);
        }
        return false;
    }
    //</editor-fold>
}
