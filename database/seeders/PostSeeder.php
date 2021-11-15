<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    protected $model = Post::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()->hasBanner(1)->create();
    }
}
