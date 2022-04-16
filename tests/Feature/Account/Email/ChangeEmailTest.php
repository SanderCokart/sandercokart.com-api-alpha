<?php

use App\Models\User;
use App\Notifications\EmailChangedNotification;
use function Pest\Laravel\patchJson;

test('user can change email and gets notified of the change',
    /**
     * @param array $data {
     * @type User  user
     * @type int  expectedStatus
     * @type string expectedMessage
     * @type boolean assertNotificationSent
     * @type boolean shouldCheckEmailHasChanged
     * @type array  submittedData {
     * @type string  email
     * }
     * @throws Exception
     */
    function (array $data) {
        Notification::fake();

        $response = patchJson(route('email.change'), $data['submittedData']);

        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);

        if ($data['shouldCheckEmailHasChanged']) expect($data['user']->email)->toBe($data['submittedData']['email']);

        if ($data['assertNotificationSent']) Notification::assertSentTo($data['user'], EmailChangedNotification::class);
        else Notification::assertNothingSent();

    })->with('changeEmailData');
