<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function makePublic(): bool
    {


        if (Storage::disk('private')->exists($this->relative_path)) {
            File::move(
                Storage::disk('private')->getDriver()->getAdapter()->applyPathPrefix($this->relative_path),
                Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($this->relative_path)
            );
            return $this->fill(['absolute_path' => Storage::disk('public')->url($this->relative_path)])->save();
        }
        return false;
    }

    public function makePrivate(): bool
    {

        if (Storage::disk('public')->exists($this->relative_path)) {
            File::move(
                Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($this->relative_path),
                Storage::disk('private')->getDriver()->getAdapter()->applyPathPrefix($this->relative_path)
            );

            return $this->fill(['absolute_path' => null])->save();
        }
        return false;
    }
}
