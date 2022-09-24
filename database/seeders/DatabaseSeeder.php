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
        $this->callWith(ArticleSeeder::class, ['count' => 10, 'articleTypeId' => ArticleType::POSTS['id'], 'amountPublished' => 5, 'userId' => $admin->id]);
        $this->callWith(ArticleSeeder::class, ['count' => 10, 'articleTypeId' => ArticleType::COURSES['id'], 'amountPublished' => 5, 'userId' => $admin->id]);
        $this->callWith(ArticleSeeder::class, ['count' => 10, 'articleTypeId' => ArticleType::TIPS_AND_TUTORIALS['id'], 'amountPublished' => 5, 'userId' => $admin->id]);
    }
}
