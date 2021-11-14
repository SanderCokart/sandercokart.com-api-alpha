<?php

namespace App\Models;

use Database\Factories\FileFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string|null $fileable_type
 * @property int|null $fileable_id
 * @property string $original_name
 * @property string $mime_type
 * @property int $is_private
 * @property string $relative_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $fileable
 * @method static FileFactory factory(...$parameters)
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @method static Builder|File whereCreatedAt($value)
 * @method static Builder|File whereFileableId($value)
 * @method static Builder|File whereFileableType($value)
 * @method static Builder|File whereId($value)
 * @method static Builder|File whereIsPrivate($value)
 * @method static Builder|File whereMimeType($value)
 * @method static Builder|File whereOriginalName($value)
 * @method static Builder|File whereRelativeUrl($value)
 * @method static Builder|File whereUpdatedAt($value)
 * @mixin Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fileable(): MorphTo
    {
        return $this->morphTo('fileable');
    }

    public function makePrivate(): void
    {
        $this->is_private = true;
        $this->save();
    }

    public function makePublic(): void
    {
        $this->is_private = false;
        $this->save();
    }

    protected static function booted()
    {
        static::deleted(function ($file) {
            Storage::disk($file['is_private'] ? 'private' : 'public')->delete($file['relative_url']);
        });
    }
}
