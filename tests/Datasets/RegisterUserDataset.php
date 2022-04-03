<?php

dataset('registerUserData', function () {
    return [
        ['assertedStatus' => 201, 'user' => ['name' => 'John Doe', 'password' => 'Pa$$w0rd', 'email' => 'john@email.com']],
        ['assertedStatus' => 422, 'user' => ['name' => 'John Doe', 'password' => 'bad password', 'email' => 'john@email.com']]
    ];
});
