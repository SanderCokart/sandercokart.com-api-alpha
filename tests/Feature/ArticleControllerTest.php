<?php

use App\Models\Article;
use App\Models\ArticleType;
use App\Models\File;
use Database\Seeders\ArticleTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{getJson, postJson, seed};

afterAll(function () {
    Storage::disk('public')->deleteDirectory('testing');
    Storage::disk('private')->deleteDirectory('testing');
});

beforeEach(function () {
    seed([ArticleTypeSeeder::class, RoleSeeder::class]);
});

it('has a working Article index page for all ArticleType relationships', function () {
    ArticleType::each(function ($articleType) {
        Article::factory()
            ->for($articleType, 'articleType')
            ->count(3)
            ->create();
    });

    ArticleType::each(function ($articleType) {
        getJson('/articles/' . $articleType->name)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('articles', 3)
                    ->hasAll(['links', 'meta']);
            });
    });
});

it('can create new Article models for all ArticleType relationships', function () {


    function assertArticlePostRequest(int $expectedStatus)
    {
        ArticleType::each(function ($articleType) use ($expectedStatus) {
            $payload = [
                'title' => 'Test Article',
                'excerpt' => 'This is a test article',
                'markdown' => 'Test Article Body',
                'article_banner_id' => File::factory()->create()->id,
                'article_type_id' => $articleType->id,
            ];

            postJson('/articles', $payload)
                ->assertStatus($expectedStatus)
                ->assertJsonStructure(['message']);
        });
    }

    assertArticlePostRequest(401);

    withUser();

    assertArticlePostRequest(403);

    withAdmin();

    assertArticlePostRequest(201);
});
