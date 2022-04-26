<?php

use App\Models\Role;
use App\Models\User;

dataset('userControllerIndexData', function () {
    //must be logged in
    yield function () {
        return [
            'user'            => null,
            'expectedStatus'  => 401,
            'expectedMessage' => 'Unauthenticated.',
        ];
    };
    //user must be admin
    yield function () {
        $user = withUser();
        return [
            'user'            => $user,
            'expectedStatus'  => 403,
            'expectedMessage' => 'This action is unauthorized.',
        ];
    };
    //admins can see all users
    yield function () {
        $user = withAdmin();
        return [
            'user'            => $user,
            'expectedStatus'  => 200,
            'expectedMessage' => null,
        ];
    };
});

dataset('userControllerStoreData', function () {
    //must be logged in
    yield function () {
        return [
            'user'            => null,
            'expectedStatus'  => 401,
            'expectedMessage' => 'Unauthenticated.',
            'expectedRoles'   => [],
            'submittedData'   => [
                'name'     => 'John Doe',
                'email'    => 'john@doe.com',
                'password' => 'Pa$$w0rd',
                'roles'    => [Role::USER],
            ],
        ];
    };
    //user must be admin
    yield function () {
        $user = withUser();
        return [
            'user'            => $user,
            'expectedStatus'  => 403,
            'expectedMessage' => 'This action is unauthorized.',
            'expectedRoles'   => [],
            'submittedData'   => [
                'name'     => 'John Doe',
                'email'    => 'john@doe.com',
                'password' => 'Pa$$w0rd',
                'roles'    => [Role::USER],
            ],
        ];
    };

    //admins can create users
    yield function () {
        $user = withAdmin();
        return [
            'user'            => $user,
            'expectedStatus'  => 201,
            'expectedMessage' => 'User created successfully.',
            'expectedRoles'   => [Role::USER],
            'submittedData'   => [
                'name'     => 'John Doe',
                'email'    => 'john@doe.com',
                'password' => 'Pa$$w0rd',
                'roles'    => [Role::USER],
            ],
        ];
    };
});

dataset('userControllerShowData', function () {
    //must be authenticated
    yield function () {
        $userToView = withLoggedOutUser();
        return [
            'user'            => null,
            'userToView'      => $userToView,
            'expectedStatus'  => 401,
            'expectedMessage' => 'Unauthenticated.',
            'expectedJson'    => null,
        ];
    };

    //user can view itself
    yield function () {
        $user = withUser();
        return [
            'user'            => $user,
            'userToView'      => $user,
            'expectedStatus'  => 200,
            'expectedMessage' => null,
            'expectedJson'    => null,
        ];
    };

    //user cannot view other user
    yield function () {
        $user = withUser();
        $userToView = User::factory()->create();

        return [
            'user'            => $user,
            'userToView'      => $userToView,
            'expectedStatus'  => 403,
            'expectedMessage' => 'This action is unauthorized.',
            'expectedJson'    => null,
        ];
    };

    //admin can view other user
    yield function () {
        $userToView = User::factory()->create();
        $user = withAdmin();
        return [
            'user'            => $user,
            'userToView'      => $userToView,
            'expectedStatus'  => 200,
            'expectedMessage' => null,
        ];
    };
});

dataset('userControllerDestroyData', function () {
    //must be authenticated
    yield function () {
        $userToDelete = withLoggedOutUser();
        return [
            'user'            => null,
            'userToDelete'    => $userToDelete,
            'expectedStatus'  => 401,
            'expectedMessage' => 'Unauthenticated.',
            'expectedJson'    => null,
        ];
    };
    //must be admin to delete other users
    yield function () {
        $user = withUser();
        $userToDelete = User::factory()->create();
        return [
            'user'            => $user,
            'userToDelete'    => $userToDelete,
            'expectedStatus'  => 403,
            'expectedMessage' => 'This action is unauthorized.',
            'expectedJson'    => null,
        ];
    };
    //unverified user cannot delete themselves
    yield function () {
        $user = withUser(true);
        $userToDelete = $user;
        return [
            'user'            => $user,
            'userToDelete'    => $userToDelete,
            'expectedStatus'  => 403,
            'expectedMessage' => 'This action is unauthorized.',
            'expectedJson'    => null,
        ];
    };
    //verified user can delete themselves
    yield function () {
        $user = withUser();
        $userToDelete = $user;
        return [
            'user'            => $user,
            'userToDelete'    => $userToDelete,
            'expectedStatus'  => 200,
            'expectedMessage' => 'Account deleted.',
        ];
    };
    //admin can delete any user
    yield function () {
        $user = withAdmin();
        $userToDelete = User::factory()->create();
        return [
            'user'            => $user,
            'userToDelete'    => $userToDelete,
            'expectedStatus'  => 200,
            'expectedMessage' => 'User deleted.',
        ];
    };
});
