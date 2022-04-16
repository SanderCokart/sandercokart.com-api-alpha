<?php

dataset('compromisedEmailData', function () {
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('email_changes', $identifier, $token, $user);

        return [
            'user'                       => $user,
            'expectedStatus'             => 200,
            'expectedMessage'            => 'Email changed successfully, please check your email and follow the link to re-verify.',
            'assertNotificationSent'     => true,
            'shouldCheckEmailHasChanged' => true,
            'submittedData'              => [
                'email'                 => 'test@test.com',
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'token'                 => $token,
                'identifier'            => $identifier,
            ],
        ];
    };

    //invalid token
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('email_changes', $identifier, $token, $user);

        return [
            'user'                       => $user,
            'expectedStatus'             => 404,
            'expectedMessage'            => 'Invalid identifier and or token.',
            'assertNotificationSent'     => false,
            'shouldCheckEmailHasChanged' => false,
            'submittedData'              => [
                'email'                 => 'test@test.com',
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'token'                 => 'invalid token',
                'identifier'            => $identifier,
            ],
        ];
    };

    //invalid identifier
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('email_changes', $identifier, $token, $user);

        return [
            'user'                       => $user,
            'expectedStatus'             => 404,
            'expectedMessage'            => 'Invalid identifier and or token.',
            'assertNotificationSent'     => false,
            'shouldCheckEmailHasChanged' => false,
            'submittedData'              => [
                'email'                 => 'test@test.com',
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'token'                 => $token,
                'identifier'            => 'invalid identifier',
            ],
        ];
    };

    //while authenticated
    yield function () {
        $user = withUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('email_changes', $identifier, $token, $user);

        return [
            'user'                       => $user,
            'expectedStatus'             => 401,
            'expectedMessage'            => 'Unauthorized.',
            'assertNotificationSent'     => false,
            'shouldCheckEmailHasChanged' => false,
            'submittedData'              => [
                'email'                 => 'test@test.com',
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'token'                 => $token,
                'identifier'            => $identifier,
            ],
        ];
    };
});
