<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now()->toDateTimeLocalString(),
            'password'          => bcrypt('Pa$$w0rd'),
        ];
    }

    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function unencrypted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'password' => 'Pa$$w0rd',
            ];
        });
    }

    public function createAdmin()
    {
        return User::factory()
                   ->hasAttached(Role::find([
                       Role::ADMIN,
                   ]))
                   ->create([
                       'email'    => $this->faker->unique()->safeEmail(),
                       'name'     => $this->faker->name(),
                       'password' => bcrypt('Pa$$w0rd'),
                   ]);
    }

    public function createUser()
    {
        return User::factory()
                   ->hasAttached(Role::find([
                       Role::USER,
                   ]))
                   ->create([
                       'email'    => $this->faker->unique()->safeEmail(),
                       'name'     => $this->faker->name(),
                       'password' => bcrypt('Pa$$w0rd'),
                   ]);
    }
}
