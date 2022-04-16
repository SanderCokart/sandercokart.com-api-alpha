<?php

use App\Models\Article;
use App\Models\ArticleBanner;
use App\Models\ArticleType;
use App\Models\File;
use Database\Seeders\ArticleTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{deleteJson, getJson, patchJson, postJson, seed};
use function PHPUnit\Framework\assertNull;

beforeEach(function () {
    seed([ArticleTypeSeeder::class, RoleSeeder::class]);
});

afterEach(function () {
    Storage::disk('public')->deleteDirectory('testing');
    Storage::disk('private')->deleteDirectory('testing');
});

/**
 * Generate articles for each ArticleType where a specified amount is published
 * @param int $articlesPerType - amount of articles to generate for each ArticleType
 * @param int $articlesPublished - amount of articles to publish
 * @return void
 */
function generateArticles(int $articlesPerType, int $articlesPublished): void
{
    ArticleType::each(function ($articleType) use ($articlesPerType, $articlesPublished) {
        Article::factory()
            ->for($articleType, 'articleType')
            ->count($articlesPerType)
            ->sequence(function (Sequence $sequence) use ($articlesPublished) {
                return $sequence->index < $articlesPublished ? ['published_at' => now()->toDateTimeString()] : [];
            })
            ->create();
    });
}


test('admins can see all Article models on index page across all ArticleType relationships', function () {
    generateArticles(10, 5);
    withAdmin();

    ArticleType::each(function ($articleType) {
        getJson('/articles/' . $articleType->name)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('articles', 10)
                    ->hasAll(['links', 'meta']);
            });
    });
});

test('users can only see published Article models on index page across all ArticleType relationships', function () {
    generateArticles(10, 5);

    ArticleType::each(function ($articleType) {
        getJson('/articles/' . $articleType->name)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has('articles', 5)
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
                'article_banner_id' => ArticleBanner::factory()->create()->id,
                'article_type_id' => $articleType->id,
                'published' => false,
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

it('can update an existing Article model for all ArticleType relationships, only admins can update', function () {
    function assertArticleUpdateRequest(int $expectedStatus)
    {
        ArticleType::each(function ($articleType) use ($expectedStatus) {
            $article = Article::factory()->for($articleType, 'articleType')->create();

            $payload = [
                'title' => 'Test Article',
                'excerpt' => 'This is a test article',
                'markdown' => 'Test Article Body',
                'article_banner_id' => ArticleBanner::factory()->create()->id,
                'article_type_id' => $articleType->id,
                'published' => false,
            ];

            patchJson('/articles/' . $article->id, $payload)
                ->assertStatus($expectedStatus)
                ->assertJsonStructure(['message']);
        });
    }

    assertArticleUpdateRequest(401);

    withUser();

    assertArticleUpdateRequest(403);

    withAdmin();

    assertArticleUpdateRequest(200);
});

test('articles published via update updated urls in the markdown', function () {
    withAdmin();
    $image = File::factory()->create();
    $article = Article::factory()->create(['markdown' => '![Test image](http://192.168.2.160/files/' . $image->id . ')']);

    $payload = [
        'title' => $article->title,
        'excerpt' => $article->excerpt,
        'markdown' => $article->markdown,
        'article_banner_id' => $article->article_banner_id,
        'article_type_id' => $article->article_type_id,
        'published' => true,
    ];


    patchJson('/articles/' . $article->id, $payload)
        ->assertStatus(200)
        ->assertJsonStructure(['message']);

    $article = Article::find($article->id);

    $updatedImage = File::find($image->id);

    $this->assertEquals('![Test image](http://192.168.2.160/' . $updatedImage->relative_url . ')', $article->markdown);
});

it('can delete an existing Article model across all ArticleType relationships, only admins can delete', function () {
    function assertArticleDeleteRequest(int $expectedStatus, bool $expectedToWork)
    {
        ArticleType::each(function ($articleType) use ($expectedToWork, $expectedStatus) {
            $article = Article::factory()->for($articleType, 'articleType')->create();
            deleteJson('/articles/' . $article->id)
                ->assertStatus($expectedStatus)
                ->assertJsonStructure(['message']);
            if ($expectedToWork) {
                assertNull(Article::find($article->id));
            }
        });
    }

    assertArticleDeleteRequest(401, false);

    withUser();

    assertArticleDeleteRequest(403, false);

    withAdmin();

    assertArticleDeleteRequest(200, true);
});
