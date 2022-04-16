<?php

use App\Models\User;
use App\Notifications\EmailChangedNotification;
use function Pest\Laravel\patchJson;

test('user can reset a compromised email address',
    /**
     * @param array $data {
     * @type User  user
     * @type int  expectedStatus
     * @type string expectedMessage
     * @type boolean assertNotificationSent
     * @type boolean shouldCheckEmailHasChanged
     * @type array  submittedData {
     * @type string  email
     * @type string  password
     * @type string  password_confirmation
     * }
     * @throws Exception
     */
    function (array $data) {
        Notification::fake();

        $response = patchJson(route('email.compromised'), $data['submittedData']);
        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);

        if ($data['shouldCheckEmailHasChanged']) expect($data['user']->refresh()->email)->toBe($data['submittedData']['email']);

        if ($data['assertNotificationSent']) Notification::assertSentTo($data['user'], EmailChangedNotification::class);
        else Notification::assertNothingSent();
    })->with('compromisedEmailData');
