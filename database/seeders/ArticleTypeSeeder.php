<?php

namespace Database\Seeders;

use App\Models\ArticleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ArticleType::insertOrIgnore([
            ['name' => 'posts'],
            ['name' => 'tips-&-tutorials'],
            ['name' => 'courses'],
        ]);
    }
}
