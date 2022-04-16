<?php

dataset('changePasswordData', function () {
    //test user can change password
    yield function () {
        $user = withUser();
        return [
            'user'                          => $user,
            'expectedStatus'                => 200,
            'expectedMessage'               => 'Password changed successfully.',
            'assertNotificationSent'        => true,
            'shouldCheckPasswordHasChanged' => true,
            'submittedData'                 => [
                'current_password'      => 'Pa$$w0rd',
                'password'              => 'Pa$$w0rd123',
                'password_confirmation' => 'Pa$$w0rd123',
            ],
        ];
    };

    //test unauthenticated user can't change password
    yield function () {
        $user = withLoggedOutUser();
        return [
            'user'                          => $user,
            'expectedStatus'                => 401,
            'expectedMessage'               => 'Unauthenticated.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
                'current_password'      => 'Pa$$w0rd',
                'password'              => 'Pa$$w0rd123',
                'password_confirmation' => 'Pa$$w0rd123',
            ],
        ];
    };

    //test password confirmation
    yield function () {
        $user = withUser();
        return [
            'user'                          => $user,
            'expectedStatus'                => 422,
            'expectedMessage'               => 'The password confirmation does not match.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
                'current_password'      => 'Pa$$w0rd',
                'password'              => 'Pa$$w0rd123',
                'password_confirmation' => 'Pa$$w0rd1234',
            ],
        ];
    };

    //test invalid current password
    yield function () {
        $user = withUser();
        return [
            'user'                          => $user,
            'expectedStatus'                => 422,
            'expectedMessage'               => 'This is not your current password.',
            'assertNotificationSent'        => false,
            'shouldCheckPasswordHasChanged' => false,
            'submittedData'                 => [
                'current_password'      => 'Pa$$w0rd123',
                'password'              => 'Pa$$w0rd123',
                'password_confirmation' => 'Pa$$w0rd123',
            ],
        ];
    };

});
