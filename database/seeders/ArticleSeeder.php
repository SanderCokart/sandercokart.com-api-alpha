<?php

namespace Database\Seeders;

use App\Models\{Article, ArticleBanner, File, User};
use Faker\Factory;
use Illuminate\Database\Seeder;
use Str;

class ArticleSeeder extends Seeder
{
    public function run(User $user, int $articleTypeId, int $count, bool $published): void
    {
        $faker = Factory::create();
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $title = $faker->unique()->words(rand(1, 10), true);
            $timestamp = now()->toDateTimeLocalString();
            $data[] = [
                'title'           => $title,
                'slug'            => Str::slug($title),
                'markdown'        => file_get_contents(base_path('stubs/markdown.stub')),
                'excerpt'         => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, voluptatibus.',
                'article_type_id' => $articleTypeId,
                'user_id'         => $user->id,
                'created_at'      => $timestamp,
                'updated_at'      => $timestamp,
                'published_at'    => $published ? $timestamp : null,
            ];
        }

        $chunks = array_chunk($data, 50);
        foreach ($chunks as $chunk) {
            Article::insert($chunk);
        }

        Article::all()->each(function (Article $article) {
            $article->banner()->sync([File::factory()->fromModel($article)->create()->id]);
        });
    }
}
