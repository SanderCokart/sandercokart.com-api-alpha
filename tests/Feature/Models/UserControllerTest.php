<?php

use App\Models\Role;
use App\Models\User;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('only admins can request a list of users', function () {
    $response = getJson('/users');
    expect($response->status())->toBe(401);

    withUser();
    $response = getJson('/users');
    expect($response->status())->toBe(403);

    withAdmin();
    $response = getJson('/users');
    expect($response->status())->toBe(200);
})->only();

test('only admins can create users and must have "user" Role', function () {
    $submittedData = ['name' => 'John Doe', 'email' => 'john@doe.com', 'password' => 'Pa$$w0rd'];

    $response = postJson('/users', $submittedData);
    expect($response->status())->toBe(401);
    expect($response->json('message'))->toBe('Unauthenticated.');


    withUser();
    $response = postJson('/users', $submittedData);
    expect($response->status())->toBe(403);
    expect($response->json('message'))->toBe('This action is unauthorized.');

    withAdmin();
    $response = postJson('/users', $submittedData);
    expect($response->status())->toBe(201);
    expect($response->json('message'))->toBe('User created successfully.');

    expect(User::find(3)->roles->contains(Role::USER))->toBeTrue();
})->only();

test('only admins can see an individual user or the user itself', function () {
    $user1 = User::factory()->create();
    $response = getJson('/users/' . $user1->id);
    expect($response->status())->toBe(401);

    $user2 = withUser();
    $response = getJson('/users/' . $user2->id);
    expect($response->status())->toBe(200);

    $user3 = withUser();
    $response = getJson('/users/' . $user1->id);
    expect($response->status())->toBe(403);

    withAdmin();
    $response = getJson('/users/1');
    expect($response->status())->toBe(200);
})->only();


test('admins can delete users and verified users can delete themselves', function () {
    //not authorized
    $user1 = User::factory()->createUser();
    $response = deleteJson('/users/' . $user1->id);
    expect($response->status())->toBe(401);

    //forbidden
    $user2 = withUser();
    $response = deleteJson('/users/' . $user1->id);
    expect($response->status())->toBe(403);

    //unverified user cannot delete themselves
    $user3 = withUser(true);
    $response = deleteJson('/users/' . $user3->id);
    expect($response->status())->toBe(403);

    //verified user can delete themselves
    $user4 = withUser();
    $response = deleteJson('/users/' . $user4->id);
    expect($response->status())->toBe(200);

    //admin can delete any user
    $user4 = withAdmin();
    $response = deleteJson('/users/' . $user2->id);
    expect($response->status())->toBe(200);
})->only();
