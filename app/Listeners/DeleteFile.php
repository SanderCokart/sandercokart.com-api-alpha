<?php

namespace App\Listeners;

use App\Events\FileModelDeleted;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class DeleteFile
{
    public File $file;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Handle the event.
     *
     * @param FileModelDeleted $event
     * @return void
     */
    public function handle(FileModelDeleted $event): void
    {
        $file = $event->file;
        Storage::disk('private')->delete($file->relative_path);
        Storage::disk('public')->delete($file->relative_path);
    }
}
