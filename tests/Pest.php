<?php

use App\Models\User;
use Database\Seeders\ArticleTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use function Pest\Laravel\seed;

uses(TestCase::class, RefreshDatabase::class)->in(__DIR__);

uses()->group('account')->in('./Feature/Account');
uses()->group('email')->in('./Feature/Account/Email');
uses()->group('password')->in('./Feature/Account/Password');
uses()->group('models')->in('./Feature/Models');

uses()->beforeEach(function () {
    seed([ArticleTypeSeeder::class, RoleSeeder::class]);
})->afterEach(function () {
    Storage::disk('public')->deleteDirectory('testing');
    Storage::disk('private')->deleteDirectory('testing');
})->in(__DIR__);

function withUser(bool $unverified = false): User
{
    $user = User::factory()->createUser($unverified);
    Sanctum::actingAs($user);

    return $user;
}

function withUnverifiedUser(): User
{

}


function withAdmin(): User
{
    $user = User::factory()->createAdmin();
    Sanctum::actingAs($user);
    return $user;
}

function withLoggedOutUser(): User
{
    return User::factory()->create();
}

function withDummyUser(): User
{
    return User::factory()->make();
}

function actingAs(Authenticatable $user, ?array $abilities = [], ?string $guard = 'sanctum'): Authenticatable
{
    return Sanctum::actingAs($user, $abilities, $guard);
}
