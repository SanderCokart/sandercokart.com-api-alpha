<?php

use App\Models\ArticleType;
use function Pest\Laravel\postJson;

beforeEach(function () {
    withAdmin();
});

it('has a working ArticleType index page', function () {
    $this->get('/articleTypes')
         ->assertJsonCount(3)
         ->assertStatus(200);
});

it('can create a new ArticleType model', function () {
    withAdmin();
    $response = postJson('/articleTypes', ['name' => 'Test ArticleType1']);
    $response->assertCreated();
    expect(ArticleType::count())->toBe(4);

    withUser();
    $response = postJson('/articleTypes', ['name' => 'Test ArticleType2']);
    $response->assertForbidden();
    expect(ArticleType::count())->toBe(4);
});

it('can update a existing ArticleType model', function () {
    $articleType = ArticleType::factory()->create(['name' => 'Initial name']);

    $this->patchJson('/articleTypes/' . $articleType->id, [
        'name' => 'Updated name',
    ])->assertStatus(200);

    $this->assertDatabaseHas('article_types', [
        'id'   => $articleType->id,
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

