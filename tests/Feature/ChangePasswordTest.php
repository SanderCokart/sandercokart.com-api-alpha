<?php

use App\Models\User;
use function Pest\Laravel\postJson;

test('User can change password and user email is notified of change', function () {
    $user = withUser();
    postJson(route('password.change', [], false));
});
