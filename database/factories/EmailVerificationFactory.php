<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class EmailVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var User $user */
        $user = User::factory()->create();
        return [
            'user_id'    => $user->id,
            'identifier' => $user->generateEmailVerificationIdentifier(),
            'token'      => $user->generateEmailVerificationToken(),
            'expires_at' => now()->addHour()->toDateTimeLocalString(),
        ];
    }
}
