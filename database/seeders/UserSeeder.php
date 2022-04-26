<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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


        //for 50000 users insert into users table using insert with Str::random
//        $data = [];
//        $timeStamp = now()->toDateTimeLocalString();
//        for ($i = 0; $i < 50000; $i++) {
//            $data[] = [
//                'name'              => Str::random(10),
//                'email'             => Str::random(10) . '@gmail.com',
//                'password'          => bcrypt('Pa$$w0rd'),
//                'email_verified_at' => $timeStamp,
//                'updated_at'        => $timeStamp,
//                'created_at'        => $timeStamp,
//            ];
//        }
//
//        $chunks = array_chunk($data, 5000);
//        foreach ($chunks as $chunk) {
//            dump('inserting chunk', $chunk);
//        }

    }
}
