<?php

use App\Models\{Article, ArticleType, File};
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * Generate articles for each ArticleType where a specified amount is published
 *
 * @param int $articlesPerType - amount of articles to generate for each ArticleType
 * @param int $articlesPublished - amount of articles to publish
 *
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

dataset('ArticleIndexData', function () {
    //as user
    yield function () {
        generateArticles(10, 5);
        $user = withUser();
        return [
            'user'     => $user,
            'expected' => [
                'status' => 403,
                'count'  => null,
            ],
        ];
    };

    //as admin
    yield function () {
        generateArticles(10, 5);
        $user = withAdmin();
        return [
            'user'     => $user,
            'expected' => [
                'status' => 200,
                'count'  => 10,
            ],
        ];
    };
});


dataset('ArticleCreateData', function () {
    yield function () {
        generateArticles(1, 0);
        $user = withAdmin();
        return [
            'user'          => $user,
            'article'       => Article::first()->id,
            'submittedData' => [
                'title'             => 'Test Article',
                'excerpt'           => 'This is a test article',
                'markdown'          => 'Test Article Body',
                'published'         => false,
                'article_type_id'   => ArticleType::POSTS['id'],
                'article_banner_id' => File::factory()->create()->id,
            ],
            'expected'      => [
                'status' => 201,
                'count'  => 1,
            ],
        ];
    };
    yield function () {
        $user = withUser();
        generateArticles(1, 0);
        return [
            'user'          => $user,
            'article'       => Article::first()->id,
            'submittedData' => [
                'title'             => 'Test Article',
                'excerpt'           => 'This is a test article',
                'markdown'          => 'Test Article Body',
                'published'         => false,
                'article_type_id'   => ArticleType::POSTS['id'],
                'article_banner_id' => 1,
            ],
            'expected'      => [
                'status' => 403,
                'count'  => null,
            ],
        ];
    };
});

dataset('ArticleUpdateData', function () {
    yield function () {
        $user = withAdmin();
        generateArticles(1, 0);
        return [
            'user'            => $user,
            'articleTypeName' => ArticleType::POSTS['name'],
            'article'         => Article::first()->id,
            'submittedData'   => [
                'title'             => 'Test Article',
                'excerpt'           => 'This is a test article',
                'markdown'          => 'Test Article Body',
                'published'         => false,
                'article_banner_id' => 1,
            ],
            'expected'        => [
                'status' => 200,
                'count'  => null,
            ],
        ];
    };
});
