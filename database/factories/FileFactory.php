<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $file = new File(Storage::disk('public')->path('/static/300x300.png'));

        $vars = ['$name' => uniqid(), '$ext' => $file->getExtension()];
        $newName = strtr('$name.$ext', $vars);

        //if env is testing put in 'testing' otherwise in 'uploads'
        $directoryToStoreFile = config('app.env') === 'testing' ? 'testing' : 'uploads';

        $path = Storage::disk('private')->putFileAs($directoryToStoreFile, $file, $newName);

        return [
            'original_name' => $file->getFilename(),
            'mime_type' => $file->getMimeType(),
            'relative_url' => $path,
            'is_private' => true
        ];
    }

    public function public(): array
    {
        $file = new File(Storage::disk('public')->path('/static/300x300.png'));

        $vars = ['$name' => uniqid(), '$ext' => $file->getExtension()];
        $newName = strtr('$name.$ext', $vars);

        $path = Storage::disk('public')->putFileAs('uploads', $file, $newName);

        return [
            'original_name' => $file->getFilename(),
            'mime_type' => $file->getMimeType(),
            'relative_url' => $path,
            'is_private' => false
        ];
    }
}
