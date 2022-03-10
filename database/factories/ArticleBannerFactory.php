<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleBanner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory
 */
class ArticleBannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $file = UploadedFile::fake()->image('banner.jpg', 300, 300);
        $relativeUrl = $file->store('/uploads/models/ArticleBanner', ['disk' => 'private']);

        return [
            'relative_url' => $relativeUrl,
        ];
    }

    public function public(): array
    {
        $file = UploadedFile::fake()->image('banner.jpg', 300, 300);
        $relativeUrl = $file->store('/uploads/models/ArticleBanner', ['disk' => 'public']);

        return [
            'relative_url' => $relativeUrl,
        ];
    }
}
