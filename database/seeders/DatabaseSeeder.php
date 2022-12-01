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
        $admin = User::firstOrCreate([
            'email' => 'cokart32@gmail.com',
        ], [

            'email'    => 'cokart32@gmail.com',
            'name'     => 'Sander Cokart',
            'password' => bcrypt('Pa$$w0rd'),
            'email_verified_at' => now()
        ]);

        $admin->roles()->sync([Role::ADMIN]);

        /*ARTICLES*/
        $this->callWith(ArticleSeeder::class, [
            'count'           => 50,
            'articleTypeId'   => ArticleType::POSTS['id'],
            'amountPublished' => 25,
            'userId'          => $admin->id,
        ]);
        $this->callWith(ArticleSeeder::class, [
            'count'           => 50,
            'articleTypeId'   => ArticleType::COURSES['id'],
            'amountPublished' => 25,
            'userId'          => $admin->id,
        ]);
        $this->callWith(ArticleSeeder::class, [
            'count'           => 50,
            'articleTypeId'   => ArticleType::TIPS_AND_TUTORIALS['id'],
            'amountPublished' => 25,
            'userId'          => $admin->id,
        ]);
    }
}
