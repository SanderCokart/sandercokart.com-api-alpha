<?php

namespace App\Listeners;

use App\Events\PostStatusUpdated;
use App\Models\Post;

class ArticleStatusListener
{
    private Post $post;

    /**
     * Create the event listener.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Handle the event.
     *
     * @param PostStatusUpdated $event
     * @return void
     */
    public function handle(PostStatusUpdated $event)
    {

    }
}
