<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait CanPublicize
{
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
