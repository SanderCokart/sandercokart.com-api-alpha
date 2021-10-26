<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Storage;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
//        $fileFactory = new FileFactory();
//        $file = $fileFactory->create('test-name.jpg', 10,)->mimeType('image/jpg');
//
//        $newName = uniqid(null, true) . '.' . $file->getClientOriginalExtension();
//        $path = Storage::disk('public')->putFileAs('images', $file, $newName);

        $file = $this->faker->image('public/storage/images', 300, 300, null, false);
        dd($file);

        return [
            'name' => $newName,
            'mime_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
            'relative_path' => $path,
            'absolute_path' => Storage::disk('public')->url($path)
        ];
    }
}
