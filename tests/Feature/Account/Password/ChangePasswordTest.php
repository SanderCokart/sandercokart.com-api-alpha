<?php

use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use function Pest\Laravel\patchJson;


test('user can change their password and get notified of the change',
    /**
     * @param array $data {
     * @type User  user
     * @type int  expectedStatus
     * @type string expectedMessage
     * @type boolean assertNotificationSent
     * @type boolean shouldCheckPasswordHasChanged
     * @type array  submittedData {
     * @type string  current_password
     * @type string  password
     * @type string  password_confirmation
     * }
     * @throws Exception
     */
    function (array $data) {
        Notification::fake();

        $response = patchJson(route('password.change'), $data['submittedData']);

        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);

        if ($data['shouldCheckPasswordHasChanged']) expect(Hash::check($data['submittedData']['password'], $data['user']->password))->toBe(true);

        if ($data['assertNotificationSent']) Notification::assertSentTo($data['user'], PasswordChangedNotification::class);
        else Notification::assertNothingSent();
    })->with('changePasswordData');
