<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use function Pest\Laravel\{postJson};

it('can register new users and sent the verify email notification', function () {
    Notification::fake();

    $payload = [
        'name' => 'John Doe',
        'email' => 'john@doe.com',
        'password' => 'Pa$$w0rd',
    ];

    $response = postJson('/account/register', $payload);
    $response->assertStatus(201);
    Notification::assertSentTo(User::find(1), VerifyEmail::class);
});
