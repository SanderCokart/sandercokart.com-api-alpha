<?php

dataset('registerUserData', function () {
    return [
        ['assertedStatus' => 201, 'user' => ['name' => 'John Doe', 'password' => 'Pa$$w0rd', 'email' => 'john@email.com']],
        ['assertedStatus' => 422, 'user' => ['name' => 'John Doe', 'password' => 'bad password', 'email' => 'john@email.com']]
    ];
});


dataset('verifyEmailData', function () {
    /*test if invalid identifier is caught*/
    yield function () {
        $user = withUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $generatedUrl = $user->generateUrlWithIdentifierAndToken('email_verifications', 'verify', 'email.verify', true, $identifier, $token);
        $urlToTest = $generatedUrl;
        return ['assertedStatus' => 200, 'assertedMessage' => 'Email has been verified!', 'urlToTest' => $urlToTest];

    };

    /*test if invalid token is caught*/
    yield function () {
        $user = withUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $generatedUrl = $user->generateUrlWithIdentifierAndToken('email_verifications', 'verify', 'email.verify', true, $identifier, $token);
        $urlToTest = route('email.verify', ['identifier' => $identifier, 'token' => 'bad token'], false);
        return ['assertedStatus' => 404, 'assertedMessage' => 'Invalid verification identifier and or token', 'urlToTest' => $urlToTest];
    };

    /*test if invalid identifier is caught*/
    yield function () {
        $user = withUser();
        $identifier = $user->generateIdentifier();
        $token = $user->generateToken();
        $generatedUrl = $user->generateUrlWithIdentifierAndToken('email_verifications', 'verify', 'email.verify', true, $identifier, $token);
        $urlToTest = route('email.verify', ['identifier' => 'bad identifier', 'token' => $token]);
        return ['assertedStatus' => 404, 'assertedMessage' => 'Invalid verification identifier and or token', 'urlToTest' => $urlToTest];
    };
});
