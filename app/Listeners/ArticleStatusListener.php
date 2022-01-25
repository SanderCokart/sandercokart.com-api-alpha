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
        if ($event->post->status->id === Status::PUBLISHED) {

            $patternToGetUrl = '%\Q' . config('app.local_url') . '/files/\E\d%';
            $patternToGetId = '%\d+$%';

            $markdown = $event->post->markdown;

            preg_match($patternToGetUrl, $markdown, $urls);

            foreach ($urls as $url) {
                preg_match($patternToGetId, $url, $ids);
                $id = $ids[0];

                $file = File::find($id);
                $file->makePublic();

                $newUrl = config('app.local_url') . '/' . $file->relative_url;
                $markdown = str_replace($url, $newUrl, $markdown);
            }

            $event->post->fill(['markdown' => $markdown])->save();

        } else {
            $patternToGetPublicUrl = '%\Q' . config('app.local_url') . '/uploads/\E.+\.[A-z0-9]+%';
            $patternToGetRelativeUrl = '%\Quploads/\E.+\.[A-z0-9]+%';

            $markdown = $event->post->markdown;

            preg_match($patternToGetPublicUrl, $markdown, $urls);

            foreach ($urls as $url) {
                preg_match($patternToGetRelativeUrl, $url, $relativeUrls);
                $relativeUrl = $relativeUrls[0];

                $file = File::where('relative_url', $relativeUrl)->firstOrFail();
                $file->makePrivate();

                $newUrl = config('app.local_url') . '/files/' . $file->id;
                $markdown = str_replace($url, $newUrl, $markdown);
            }
        }
    }
}
