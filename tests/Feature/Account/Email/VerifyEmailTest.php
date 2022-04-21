<?php

use function Pest\Laravel\postJson;

test('user can verify email', function ($data) {
    Notification::fake();

    postJson($data['urlToTest'])
        ->assertStatus($data['assertedStatus']);

})->with('verifyEmailData');
