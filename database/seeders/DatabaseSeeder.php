<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            ArticleTypeSeeder::class,
            UserSeeder::class,
//            ArticleBannerSeeder::class,
//            ArticleSeeder::class,
        ]);
    }
}
