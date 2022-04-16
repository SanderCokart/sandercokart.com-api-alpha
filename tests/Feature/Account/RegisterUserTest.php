<?php

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use function Pest\Laravel\{postJson};


test('can register new users', function ($assertedStatus, $user) {
    Notification::fake();
    postJson(route('account.register'), $user)
        ->assertStatus($assertedStatus)
        ->assertJsonStructure(['message']);

    if ($assertedStatus === 201)
        Notification::assertSentTo(User::find(1), EmailVerificationNotification::class);
})->with('registerUserData');

test('user can verify email', function ($data) {
    Notification::fake();
    postJson($data['urlToTest'])
        ->assertStatus($data['assertedStatus'])
        ->assertJsonFragment(['message' => $data['assertedMessage']]);

})->with('verifyEmailData');
