<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        User::factory()
            ->hasAttached(Role::find([
                Role::ADMIN,
            ]))
            ->create([
                'email' => 'cokart32@gmail.com',
                'name' => 'Sander Cokart',
                'password' => bcrypt('Pa$$w0rd'),
            ]);
    }
}
