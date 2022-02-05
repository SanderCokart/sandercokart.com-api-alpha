<?php

namespace App\Models;

use App\Events\FileModelDeleted;
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

    protected $dispatchesEvents = [
        'deleted' => FileModelDeleted::class
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo('fileable', 'fileable_type', 'fileable_id');
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
