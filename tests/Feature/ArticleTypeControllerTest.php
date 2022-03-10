<?php

use App\Models\ArticleType;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->hasAttached(Role::find(Role::ADMIN))->create();
    actingAs($user);
});

it('has a working ArticleType index page', function () {
    ArticleType::factory()->count(3)->create();
    $this->get('/articleTypes')
        ->assertJsonCount(3)
        ->assertStatus(200);
});

it('can create a new ArticleType model', function () {
    $this->postJson('/articleTypes', [
        'name' => 'Test ArticleType',
    ])->assertStatus(201);
});

it('can update a existing ArticleType model', function () {
    $articleType = ArticleType::factory()->create(['name' => 'Initial name']);

    $this->patchJson('/articleTypes/' . $articleType->id, [
        'name' => 'Updated name',
    ])->assertStatus(200);

    $this->assertDatabaseHas('article_types', [
        'id' => $articleType->id,
        'name' => 'Updated name',
    ]);
});

it('can delete a ArticleType model', function () {
    $articleType = ArticleType::factory()->create();

    $this->deleteJson('/articleTypes/' . $articleType->id)
        ->assertStatus(200);

    $this->assertDatabaseMissing('article_types', [
        'id' => $articleType->id,
    ]);
});

