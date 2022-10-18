<?php

namespace Database\Factories;

use App\Enums\DisksEnum;
use App\Models\Article;
use App\Models\File;
use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'is_private' => true,
        ];
    }

    //state is_private true
    public function public(): FileFactory
    {
        return $this->state([
            'is_private' => false,
        ]);
    }

    public function configure(): FileFactory
    {
        $uploadedFile = Uploadedfile::fake()
            ->image('300x300.png', 300, 300);

        return $this->afterMaking(function (File $file) use ($uploadedFile) {
            $fus = new FileUploadService();

            $file->relative_path =
                $fus->handleFileUpload(
                    $uploadedFile,
                    Article::class,
                    $file->is_private ? DisksEnum::PRIVATE : DisksEnum::PUBLIC
                );

        });
    }
}
