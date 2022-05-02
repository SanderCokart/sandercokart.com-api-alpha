<?php

use App\Models\User;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('only admins can request a list of users',
    /**
     * @param array $data {
     * @type User  user
     * @type int  expectedStatus
     * @type string expectedMessage
     */
    function (array $data) {
        $response = getJson(route('users.index'));
        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);
    })->with('userControllerIndexData');

test('only admins can create users and must have "user" Role',
    /**
     * @param array $data {
     * @type User  user
     * @type int  expectedStatus
     * @type array expectedRoles
     * @type string expectedMessage
     * @type array  submittedData {
     * @type string  name
     * @type string  email
     * @type string  password
     * @type array roles
     * }
     */
    function (array $data) {
        $response = postJson(route('users.store'), $data['submittedData']);
        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);

        expect(User::find(2)?->roles
                ->contains(function ($value) use ($data) {
                    return in_array($value->id, $data['expectedRoles']);
                }) ?? false
        )->toBe(! ! $data['expectedRoles']);

    })->with('userControllerStoreData');

test('only admins can see an individual user or the user itself',
    /**
     * @param array $data {
     * @type User  user
     * @type User  userToView
     * @type int  expectedStatus
     * @type string expectedMessage
     */
    function (array $data) {
        $response = getJson(route('users.show', ['user' => $data['userToView']->id]));
        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);
    })->with('userControllerShowData');


test('admins can delete users and verified users can delete themselves',
    /**
     * @param array $data {
     * @type User  user
     * @type User  userToDelete
     * @type int  expectedStatus
     * @type string expectedMessage
     */
    function (array $data) {
        $response = deleteJson(route('users.destroy', ['user' => $data['userToDelete']->id]));
        expect($response->status())->toBe($data['expectedStatus']);
        expect($response->json('message'))->toBe($data['expectedMessage']);
    })->with('userControllerDestroyData');
