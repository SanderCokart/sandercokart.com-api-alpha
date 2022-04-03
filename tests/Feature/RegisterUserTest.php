<?php

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use function Pest\Laravel\{postJson};


it('can register new users', function ($assertedStatus, $user) {
    Notification::fake();
    postJson('/account/register', $user)
        ->assertStatus($assertedStatus)
        ->assertJsonStructure(['message']);

    if ($assertedStatus === 201)
        Notification::assertSentTo(User::find(1), VerifyEmailNotification::class);
})->with('registerUserData');

it('can verify email', function () {
    /** @var User $user */
    $user = withUser();

    $identifier = $user->generateEmailVerificationIdentifier();
    $token = $user->generateEmailVerificationToken();

    $user->insertVerificationTokenIntoDatabase($identifier, $token);

    postJson('/account/email/verify', ['identifier' => $identifier, 'token' => $token])
        ->assertStatus(200)
        ->assertJsonStructure(['message']);
})->only();
