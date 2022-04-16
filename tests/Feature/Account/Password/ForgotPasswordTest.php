<?php

use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\ForgotPasswordNotification;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use JetBrains\PhpStorm\ArrayShape;


test('user can issue forgot password request', function ($data) {
    Notification::fake();
    $response = postJson(route('password.forgot'), $data['submittedData']);

    $response->assertStatus($data['assertedStatus']);
    $response->assertJsonFragment(['message' => $data['assertedMessage']]);

    if ($data['assertNotificationSent']) {
        Notification::assertSentTo($data['user'], ForgotPasswordNotification::class);
    } else {
        Notification::assertNothingSent();
    }
})->with('forgotPasswordData');


test('user can reset password', function ($data) {
    Notification::fake();

    $response = patchJson(route('password.reset'), $data['submittedData']);
    $response->assertStatus($data['assertedStatus']);
    $response->assertJsonFragment(['message' => $data['assertedMessage']]);

    $user = $data['user']->refresh();

    expect(Hash::check($data['submittedData']['password'], $user->password))->toBe(true);

    if ($data['assertNotificationSent']) {
        Notification::assertSentTo($data['user'], PasswordChangedNotification::class);
    } else {
        Notification::assertNothingSent();
    }
})->with('resetPasswordData');
