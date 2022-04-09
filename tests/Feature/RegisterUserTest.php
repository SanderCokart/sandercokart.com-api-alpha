<?php

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use function Pest\Laravel\{postJson};


it('can register new users', function ($assertedStatus, $user) {
    Notification::fake();
    postJson(route('account.register'), $user)
        ->assertStatus($assertedStatus)
        ->assertJsonStructure(['message']);

    if ($assertedStatus === 201)
        Notification::assertSentTo(User::find(1), VerifyEmailNotification::class);
})->with('registerUserData');

it('can verify email', function ($data) {
    postJson($data['urlToTest'])
        ->assertStatus($data['assertedStatus'])
        ->assertJsonStructure(['message'])
        ->assertJsonFragment(['message' => $data['assertedMessage']]);

})->with('verifyEmailData')->only();
