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
        for ($i = 0; $i < 30; $i++) {
            Post::factory()->create(['user_id' => 1, 'created_at' => now()->addMinute($i)]);
        }
    }
}
