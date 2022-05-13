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
        $this->update(['is_private' => true]);
        return Storage::move('/public/' . $this->relative_path, '/private/' . $this->relative_path);
    }

    public function makePublic(): bool
    {
        $this->update(['is_private' => false]);
        return Storage::move('/private/' . $this->relative_path, '/public/' . $this->relative_path);
    }
    //</editor-fold>
}
