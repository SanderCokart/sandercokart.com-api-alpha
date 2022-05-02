<?php


use App\Models\Article;

dataset('ArticleBannerIndexData', function () {
    //as normal user
    yield function () {
        $user = withUser();
        return [
            'user'     => $user,
            'expected' => [
                'status'  => 403,
                'message' => 'This action is unauthorized.',
                'length'  => null,
            ],
        ];
    };
    //as admin
    yield function () {
        $user = withAdmin();
        return [
            'user'     => $user,
            'expected' => [
                'status'  => 200,
                'message' => null,
                'length'  => 10,
            ],
        ];
    };
});

dataset('ArticleBannerShowData', function () {
    //view a private banner as normal user
    yield function () {
        $user = withUser();
        $article = Article::factory()->create();
        $articleBanner = $article->banner;
        return [
            'user'          => $user,
            'article'       => $article,
            'articleBanner' => $articleBanner,
            'expected'      => [
                'status'  => 403,
                'message' => 'This action is unauthorized.',
            ],
        ];
    };
    //view a private banner as admin
    yield function () {
        $user = withAdmin();
        $article = Article::factory()->create();
        $articleBanner = $article->banner;
        return [
            'user'          => $user,
            'article'       => $article,
            'articleBanner' => $articleBanner,
            'expected'      => [
                'status'  => 200,
                'message' => null,
            ],
        ];
    };

    //view public banner as normal user
    yield function () {
        $user = withUser();
        $article = Article::factory()->create();
        $articleBanner = $article->banner;
        $article->publish();

        return [
            'user'          => $user,
            'article'       => $article,
            'articleBanner' => $articleBanner,
            'expected'      => [
                'status'  => 200,
                'message' => null,
            ],
        ];
    };
});
