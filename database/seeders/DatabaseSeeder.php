<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Status;
use App\Models\User;
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
            StatusSeeder::class,
            RoleSeeder::class,
            ArticleTypeSeeder::class
//            UserSeeder::class,
        ]);

        $user = User::factory()->create([
            'email' => 'cokart32@gmail.com',
            'name' => 'Sander Cokart',
            'password' => bcrypt('Pa$$w0rd'),
        ]);
    }
}
