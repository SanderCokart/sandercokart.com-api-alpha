<?php

dataset('changeEmailData', function () {
    yield function () {
        $user = withUser();
        return [
            'user'                       => $user,
            'expectedStatus'             => 200,
            'expectedMessage'            => 'Email changed successfully, please check your email and follow the link to re-verify.',
            'assertNotificationSent'     => true,
            'shouldCheckEmailHasChanged' => true,
            'submittedData'              => [
                'email' => 'test@test.com',
            ],
        ];
    };

    //with invalid email
    yield function () {
        $user = withUser();
        return [
            'user'                       => $user,
            'expectedStatus'             => 422,
            'expectedMessage'            => 'The email must be a valid email address.',
            'assertNotificationSent'     => false,
            'shouldCheckEmailHasChanged' => false,
            'submittedData'              => [
                'email' => 'test',
            ],
        ];
    };

    yield function () {
        $user = withLoggedOutUser();
        return [
            'user'                       => $user,
            'expectedStatus'             => 401,
            'expectedMessage'            => 'Unauthenticated.',
            'assertNotificationSent'     => false,
            'shouldCheckEmailHasChanged' => false,
            'submittedData'              => [
                'email' => 'test@test.com',
            ],
        ];
    };

});
