<?php

namespace Database\Seeders;

use App\Models\{Article, ArticleType, File};
use Database\Factories\FileFactory;
use Faker\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Str;

class ArticleSeeder extends Seeder
{
//    public function run(User $user, int $articleTypeId, int $count, bool $published): void
//    {
//        $faker = Factory::create();
//        $data = [];
//
//        for ($i = 0; $i < $count; $i++) {
//            $title = $faker->unique()->words(rand(1, 10), true);
//            $timestamp = now()->toDateTimeLocalString();
//            $data[] = [
//                'title'           => $title,
//                'slug'            => Str::slug($title),
//                'markdown'        => file_get_contents(base_path('stubs/markdown.stub')),
//                'excerpt'         => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, voluptatibus.',
//                'article_type_id' => $articleTypeId,
//                'user_id'         => $user->id,
//                'created_at'      => $timestamp,
//                'updated_at'      => $timestamp,
//                'published_at'    => $published ? $timestamp : null,
//            ];
//        }
//
//        $chunks = array_chunk($data, 50);
//        foreach ($chunks as $chunk) {
//            Article::insert($chunk);
//        }
//
//        Article::all()->each(function (Article $article) {
//            $article->banner()->sync([File::factory()->fromModel($article)->create()->id]);
//        });
//    }

    public function run($count = 1, $articleTypeId = ArticleType::POSTS['id'], int $amountPublished = 0, $userId = 1): void
    {
        $faker = Factory::create();
        $markdown = file_get_contents(base_path('stubs/markdown.stub'));
        $timestamp = now()->toDateTimeLocalString();

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $title = $faker->unique()->text(80);
            $excerpt = $faker->text(180);
            $shouldBePublished = ($i < $amountPublished) ? $timestamp : null;

            $data[] = [
                'title'           => $title,
                'slug'            => Str::slug($title),
                'markdown'        => $markdown,
                'excerpt'         => $excerpt,
                'article_type_id' => $articleTypeId,
                'user_id'         => $userId,
                'created_at'      => $timestamp,
                'updated_at'      => $timestamp,
                'published_at'    => $shouldBePublished,
            ];
        }

        $chunks = array_chunk($data, 50);
        foreach ($chunks as $chunk) {
            Article::insert($chunk);
        }

        $latestId = Article::latest('id')->value('id');

        $ids = [];
        for ($i = 0; $i < $count; $i++) {
            $ids[] = $latestId - $i;
        }

        Article::find($ids)->each(function (Article $article) {
            $article->banner()->sync([
                File::factory()->when($article->isPublished(), fn (FileFactory $query) => $query->public())->create()->id,
            ]);
        });
    }
}
