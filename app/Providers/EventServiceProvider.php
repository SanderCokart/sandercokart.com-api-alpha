<?php

namespace App\Providers;

use App\Events\ArticleTypeCreated;
use App\Events\ArticleTypeDeleted;
use App\Events\ArticleTypeUpdated;
use App\Events\FileModelDeleted;
use App\Events\PostStatusUpdated;
use App\Listeners\ArticleTypeCacheListener;
use App\Listeners\DeleteFile;
use App\Listeners\ArticleStatusListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        FileModelDeleted::class => [
            DeleteFile::class,
        ],
        PostStatusUpdated::class => [
            ArticleStatusListener::class
        ],
        ArticleTypeCreated::class => [
            ArticleTypeCacheListener::class
        ],
        ArticleTypeUpdated::class => [
            ArticleTypeCacheListener::class

        ],
        ArticleTypeDeleted::class => [
            ArticleTypeCacheListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
