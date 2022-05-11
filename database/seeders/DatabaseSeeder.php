<?php

namespace Database\Seeders;

use App\Models\{ArticleType, Role, User};
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
        /*APP MUST HAVE THIS*/
        $this->call([
            RoleSeeder::class,
            ArticleTypeSeeder::class,
        ]);

        /*DEFAULT USER*/
        $admin = User::factory()
                     ->hasAttached(Role::find([
                         Role::ADMIN,
                     ]))
                     ->create([
                         'email'    => 'cokart32@gmail.com',
                         'name'     => 'Sander Cokart',
                         'password' => bcrypt('Pa$$w0rd'),
                     ]);

        /*ARTICLES*/
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::POSTS['id'], 'count' => 10, 'published' => true]);
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::POSTS['id'], 'count' => 10, 'published' => false]);
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::COURSES['id'], 'count' => 10, 'published' => true]);
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::COURSES['id'], 'count' => 10, 'published' => false]);
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::TIPS_AND_TUTORIALS['id'], 'count' => 10, 'published' => true]);
        $this->callWith(ArticleSeeder::class, ['user' => $admin, 'articleTypeId' => ArticleType::TIPS_AND_TUTORIALS['id'], 'count' => 10, 'published' => false]);
    }
}
