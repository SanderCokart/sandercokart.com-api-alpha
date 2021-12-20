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
        $file = new File(Storage::disk('public')->path('/static/600x200.png'));

        $vars = ['$name' => uniqid(), '$ext' => $file->getExtension()];
        $newName = strtr('$name.$ext', $vars);

        $path = Storage::disk('public')->putFileAs('uploads', $file, $newName);

        return [
            'original_name' => $file->getFilename(),
            'mime_type' => $file->getMimeType(),
            'relative_url' => $path,
        ];
    }
}
