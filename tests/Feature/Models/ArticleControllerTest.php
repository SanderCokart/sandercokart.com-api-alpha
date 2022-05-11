<?php

use App\Models\ArticleBanner;
use App\Models\ArticleType;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{getJson, patchJson, postJson};

test('only admins can see all Article models on index page across all ArticleType relationships',
    /**
     * @param array $data {
     *
     * @type User  user
     * @type array  $expected {
     * @type int    $status
     * @type int    $count
     *}
     */
    function (array $data) {
        ArticleType::each(function ($articleType) use ($data) {
            $response = getJson(route('articles.index', ['articleType' => $articleType->name]));
            $response->assertStatus($data['expected']['status']);
            if ($response->isOk())
                $response->assertJson(function (AssertableJson $json) use ($data) {
                    $json->has('articles', $data['expected']['count'])
                         ->hasAll(['links', 'meta']);
                });
        });
    })->with('ArticleIndexData');

it('can create new Article models for all ArticleType relationships',
    /**
     * @param array $data {
     *
     * @type User  user
     * @type string $articleTypeName
     * @type int    $article
     * @type array submittedData {
     * @type string title
     * @type string excerpt
     * @type string markdown
     * @type bool published
     * }
     * @type array  expected {
     * @type int    $status
     * }
     */
    function (array $data) {
        postJson(route('articles.store', ['article' => $data['article']]), $data['submittedData'])
            ->assertStatus($data['expected']['status'])
            ->assertJsonStructure(['message']);
    })->with('ArticleCreateData');

it('can update an existing Article model for all ArticleType relationships, only admins can update',
    /**
     * @param array $data {
     *
     * @type User  user
     * @type string $articleTypeName
     * @type int    $article
     * @type array submittedData {
     * @type string title
     * @type string excerpt
     * @type string markdown
     * @type bool published
     * }
     * @type array  expected {
     * @type int    $status
     * }
     */
    function (array $data) {
        $response = patchJson(route('articles.update', ['articleType' => $data['articleTypeName'], 'article' => $data['article']]), $data['submittedData']);
        $response->assertStatus($data['expected']['status']);
        $response->assertJsonStructure(['message']);
    })->with('ArticleUpdateData');

//test('articles published via update updated urls in the markdown', function () {
//    withAdmin();
//    $image = File::factory()->create();
//    $article = Article::factory()->create(['markdown' => '![Test image](http://192.168.2.160/files/' . $image->id . ')']);
//
//    $payload = [
//        'title'             => $article->title,
//        'excerpt'           => $article->excerpt,
//        'markdown'          => $article->markdown,
//        'article_banner_id' => $article->article_banner_id,
//        'article_type_id'   => $article->article_type_id,
//        'published'         => true,
//    ];
//
//
//    patchJson('/articles/' . $article->id, $payload)
//        ->assertStatus(200)
//        ->assertJsonStructure(['message']);
//
//    $article = Article::find($article->id);
//
//    $updatedImage = File::find($image->id);
//
//    $this->assertEquals('![Test image](http://192.168.2.160/' . $updatedImage->relative_url . ')', $article->markdown);
//});

//it('can delete an existing Article model across all ArticleType relationships, only admins can delete', function () {
//    function assertArticleDeleteRequest(int $expectedStatus, bool $expectedToWork)
//    {
//        ArticleType::each(function ($articleType) use ($expectedToWork, $expectedStatus) {
//            $article = Article::factory()->for($articleType, 'articleType')->create();
//            deleteJson('/articles/' . $article->id)
//                ->assertStatus($expectedStatus)
//                ->assertJsonStructure(['message']);
//            if ($expectedToWork) {
//                assertNull(Article::find($article->id));
//            }
//        });
//    }
//
//    assertArticleDeleteRequest(401, false);
//
//    withUser();
//
//    assertArticleDeleteRequest(403, false);
//
//    withAdmin();
//
//    assertArticleDeleteRequest(200, true);
//});
