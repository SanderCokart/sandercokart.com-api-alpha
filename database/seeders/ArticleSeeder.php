<?php

namespace Database\Seeders;

use App\Models\{Article, ArticleBanner, ArticleType, User};
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = User::find(1);
        $articleTypes = ArticleType::all();
        foreach ($articleTypes as $articleType) {
            Article::factory()->count(10)->for($articleType, 'articleType')->create();
        }
    }
}
