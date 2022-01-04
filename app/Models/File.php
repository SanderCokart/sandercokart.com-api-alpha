<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    protected static function booted()
    {
        static::deleted(function ($file) {
            Storage::disk($file['is_private'] ? 'private' : 'public')->delete($file['relative_url']);
        });
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo('fileable');
    }

    public function makePrivate(): void
    {
        if (!$this->is_private) {
            $this->is_private = true;
            Storage::move('public/' . $this->relative_url, 'private/' . $this->relative_url);
            $this->save();
        }
    }

    public function makePublic(): void
    {
        if ($this->is_private) {
            $this->is_private = false;
            Storage::move('private/' . $this->relative_url, 'public/' . $this->relative_url);
            $this->save();
        }
    }
}
