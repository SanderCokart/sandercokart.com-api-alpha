<?php

dataset('compromisedPasswordData', function () {
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('password_changes', $identifier, $token, $user);

        return [
            'user'                          => $user,
            'expectedStatus'                => 200,
            'expectedMessage'               => 'Password was reset successfully and you have been logged out of all devices.',
            'assertNotificationSent'        => true,
            'shouldCheckPasswordHasChanged' => true,
            'submittedData'                 => [
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
        $user->insertTokenAndIdentifierIntoDatabase('password_changes', $identifier, $token, $user);

        return [
            'user'                          => $user,
            'expectedStatus'                => 404,
            'expectedMessage'               => 'Invalid identifier and or token.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
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
        $user->insertTokenAndIdentifierIntoDatabase('password_changes', $identifier, $token, $user);

        return [
            'user'                          => $user,
            'expectedStatus'                => 404,
            'expectedMessage'               => 'Invalid identifier and or token.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
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
        $user->insertTokenAndIdentifierIntoDatabase('password_changes', $identifier, $token, $user);

        return [
            'user'                          => $user,
            'expectedStatus'                => 401,
            'expectedMessage'               => 'Unauthorized.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'token'                 => $token,
                'identifier'            => $identifier,
            ],
        ];
    };
});
