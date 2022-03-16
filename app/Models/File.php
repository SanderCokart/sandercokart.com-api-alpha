<?php

namespace App\Models;

use App\Events\FileModelDeleted;
use App\Traits\CanPublicize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, CanPublicize;

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


}
