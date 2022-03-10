<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in(__DIR__);

//afterAll(function () {
//    Storage::disk('private')->deleteDirectory('testing');
//});


/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function withUser()
{
    $user = User::factory()->createUser();
    Sanctum::actingAs($user);
    return $user;
}

function withAdmin(): Authenticatable
{
    $user = User::factory()->createAdmin();
    Sanctum::actingAs($user);
    return $user;
}

/**
 * Set the currently logged in user for the application.
 *
 * @param Authenticatable $user
 * @param string|null $driver
 * @return Authenticatable
 */
function actingAs(Authenticatable $user, string $driver = null): Authenticatable
{
    return Sanctum::actingAs(...func_get_args());
}
