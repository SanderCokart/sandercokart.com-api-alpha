<?php

namespace Database\Seeders;

use App\Models\ArticleBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleBanner::factory()->count(200)->create();
    }
}
