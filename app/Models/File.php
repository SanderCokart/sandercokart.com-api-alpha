<?php

namespace App\Models;

use App\Listeners\FilePrivacyChanged;
use Database\Factories\FileFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\File
 *
 * @property int $id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $original_name
 * @property string $mime_type
 * @property int $is_private
 * @property string $relative_path
 * @property string|null $url
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
 * @method static Builder|File whereRelativePath($value)
 * @method static Builder|File whereUpdatedAt($value)
 * @method static Builder|File whereUrl($value)
 * @mixin Eloquent
 * @property string|null $private_url
 * @method static Builder|File wherePrivateUrl($value)
 */
class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($file) {
            $file->private_url = '/files/' . $file->getKey();
    });
    }

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

}
