<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'email' => 'cokart32@gmail.com',
            'name' => 'Sander Cokart',
            'password' => bcrypt('Pa$$w0rd'),
        ]);
    }
}
