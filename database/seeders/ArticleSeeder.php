<?php

namespace Database\Seeders;

use App\Models\{Article, ArticleBanner, ArticleType};
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Str;

class ArticleSeeder extends Seeder
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $articleTypes = collect(ArticleType::all()->modelKeys());
        $articleBanners = ArticleBanner::all()->modelKeys();

        //for i 200
        for ($i = 0; $i < 200; $i++) {
            $title = $this->faker->unique()->words(rand(1, 10), true);
            $data[] = [
                'title' => $title,
                'slug' => Str::slug($title),
                'markdown' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, voluptatibus.',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, voluptatibus.',
                'article_type_id' => $articleTypes->random(),
                'article_banner_id' => $articleBanners[$i],
                'user_id' => 1,
                'created_at' => now()->toDateTimeLocalString(),
                'updated_at' => now()->toDateTimeLocalString(),
                'published_at' => $this->faker->randomElement([
                    null,
                    now()->toDateTimeLocalString(),
                ]),
            ];
        }

        $chunks = array_chunk($data, 50);
        foreach ($chunks as $chunk) {
            Article::insert($chunk);
        }


//        $articleTypes = ArticleType::all();
//        foreach ($articleTypes as $articleType) {
//            Article::factory()->count(200)
//                ->for($articleType, 'articleType')
//                ->sequence(fn(Sequence $sequence) => ($sequence->index < 200) ? ['published_at' => now()->toDateTimeLocalString()] : [])
//                ->create();
//        }
    }
}
