<?php

it('has account/password/compromisedpassword page', function () {
    $response = $this->get('/account/password/compromisedpassword');

    $response->assertStatus(200);
});
