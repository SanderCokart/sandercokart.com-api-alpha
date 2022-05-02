<?php

use App\Models\Article;
use App\Models\ArticleBanner;
use App\Models\User;
use function Pest\Laravel\artisan;
use function Pest\Laravel\getJson;

//afterEach(function () {
//    ArticleBanner::all()->each->delete();
//    artisan('prune:files');
//});

test('only admins can index',
    /**
     * @param array $data {
     * @type User $user
     * @type array $expected {
     * @type int $status
     * @type int $length
     * @type string $message
     *  }
     * }
     */
    function (array $data) {
        ArticleBanner::factory(10)->create();
        $response = getJson('articleBanners');

        expect($response->status())->toBe($data['expected']['status']);
        expect($response->json('message'))->toBe($data['expected']['message']);
        if ($data['expected']['length']) expect($response->json())->toHaveCount($data['expected']['length']);


    })->with('ArticleBannerIndexData');

test('only admins can see private banners via show method',
    /**
     * @param array $data {
     * @type User $user
     * @type Article $article
     * @type ArticleBanner $articleBanner
     * @type array $expected {
     * @type int $status
     * @type string $message
     *  }
     * }
     */
    function (array $data) {
        $response = getJson(route('articleBanners.show', ['articleBanner' => $data['articleBanner']->id]));
        expect($response->status())->toBe($data['expected']['status']);
        expect($response->json('message'))->toBe($data['expected']['message']);
    })->with('ArticleBannerShowData')->only();

test('users can see banners', function () {

});

test('only admins can create new banners', function () {

});

