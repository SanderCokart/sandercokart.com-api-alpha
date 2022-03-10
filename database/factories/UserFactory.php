<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('Pa$$w0rd'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
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
                'email' => $this->faker->unique()->safeEmail(),
                'name' => $this->faker->name(),
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
                'email' => $this->faker->unique()->safeEmail(),
                'name' => $this->faker->name(),
                'password' => bcrypt('Pa$$w0rd'),
            ]);
    }
}
