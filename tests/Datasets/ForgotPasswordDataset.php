<?php

use App\Models\User;

dataset('forgotPasswordData', function () {
    //authenticated user
    yield function () {
        $user = withUser();
        return [
            'user'                   => $user,
            'assertedStatus'         => 401,
            'assertedMessage'        => 'Unauthorized.',
            'assertNotificationSent' => false,
            'submittedData'          => [
                'email' => $user->email
            ]
        ];
    };

    //unauthenticated user
    yield function () {
        $user = withLoggedOutUser();
        return [
            'user'                   => $user,
            'assertedStatus'         => 200,
            'assertedMessage'        => 'If a user with that email address exists, you will receive an email with instructions on how to reset your password.',
            'assertNotificationSent' => true,
            'submittedData'          => [
                'email' => $user->email
            ]
        ];
    };

    //unauthenticated user that is resetting the password for a user that does not exist
    yield function () {
        $user = withDummyUser();
        return [
            'user'                   => $user,
            'assertedStatus'         => 200,
            'assertedMessage'        => 'If a user with that email address exists, you will receive an email with instructions on how to reset your password.',
            'assertNotificationSent' => false,
            'submittedData'          => [
                'email' => $user->email
            ]
        ];
    };
});

dataset('resetPasswordData', function () {
    //authenticated user
    yield function () {
        $user = withUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('password_resets', $identifier, $token, $user);

        return [
            'user'                   => $user,
            'assertedStatus'         => 401,
            'assertedMessage'        => 'Unauthorized.',
            'assertNotificationSent' => false,
            'submittedData'          => [
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'identifier'            => $identifier,
                'token'                 => $token,
            ]
        ];
    };

    //unauthenticated user
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('password_resets', $identifier, $token, $user);

        return [
            'user'                   => $user,
            'assertedStatus'         => 200,
            'assertedMessage'        => 'Password reset successfully.',
            'assertNotificationSent' => true,
            'submittedData'          => [
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'identifier'            => $identifier,
                'token'                 => $token,
            ]
        ];
    };

    //invalid token
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('password_resets', $identifier, $token, $user);

        return [
            'user'                   => $user,
            'assertedStatus'         => 404,
            'assertedMessage'        => 'Invalid identifier and or token.',
            'assertNotificationSent' => false,
            'submittedData'          => [
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'identifier'            => $identifier,
                'token'                 => 'invalid_token',
            ]
        ];
    };

    //invalid identifier
    yield function () {
        $user = withLoggedOutUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $user->insertTokenAndIdentifierIntoDatabase('password_resets', $identifier, $token, $user);

        return [
            'user'                   => $user,
            'assertedStatus'         => 404,
            'assertedMessage'        => 'Invalid identifier and or token.',
            'assertNotificationSent' => false,
            'submittedData'          => [
                'password'              => 'Pa$$w0rd',
                'password_confirmation' => 'Pa$$w0rd',
                'identifier'            => 'invalid_identifier',
                'token'                 => $token,
            ]
        ];
    };
});
