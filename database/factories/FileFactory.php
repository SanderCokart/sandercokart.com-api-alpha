<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Str;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $file = Uploadedfile::fake()
                            ->image('300x300.png', 300, 300);
        return [
            'relative_path' => $file->store('uploads/' . $file->getMimeType(), ['disk' => 'private']),
        ];
    }

    public function withCustomPath(string $path): static
    {
        return $this->state([
            'relative_path' => Uploadedfile::fake()
                                           ->image('300x300.png', 300, 300)
                                           ->store($path, ['disk' => 'private']),
        ]);
    }

    public function fromModel(Model|string $model): FileFactory
    {
        return $this->state([
            'relative_path' => Uploadedfile::fake()
                                           ->image('300x300.png', 300, 300)
                                           ->store('uploads/models/' . $this->determineFolderName($model), ['disk' => 'private']),
        ]);
    }

    private function determineFolderName(Model|string $model): string
    {
        if ($model instanceof Model) {
            return Str::plural(Str::studly(class_basename($model)));
        } else {
            return Str::plural(Str::studly($model));
        }
    }
}
