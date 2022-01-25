<?php

namespace App\Http\Controllers\Models;

use App\Events\PostStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\File;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return PostCollection
     */
    public function index(Request $request): PostCollection
    {
        $validatedData = $request->validate(['cursor' => 'numeric|integer', 'perPage' => 'numeric|integer|max:30']);
        $perPage = $validatedData['perPage'] ?? null;
        $cursor = $validatedData['cursor'] ?? null;

        $postCollection = new PostCollection(Post::latest()->when(isset($cursor), function ($query) use ($cursor) {
            $query->where('id', '<=', $cursor);
        })->simplePaginate($perPage)->appends(['cursor' => $cursor]));

        if (!isset($cursor)) $cursor = $postCollection->collection->first()->id ?? null;

        return ($postCollection)->additional(['meta' => ['cursor' => $cursor]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return PostResource
     */
    public function store(Request $request): PostResource
    {
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'banner' => ['integer', 'required'],
            'markdown' => ['string', 'required'],
            'status' => ['integer', 'required'],
        ]);

        $post = $request->user()->posts()->create($validatedData);
        $post->banner()->sync(File::find($validatedData['banner']));
        $post->status()->sync(Status::find($validatedData['status']));
        $post->refresh();

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function update(Request $request, Post $post): Response
    {
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'banner' => ['integer', 'required'],
            'markdown' => ['string', 'required'],
            'status' => ['integer', 'required'],
        ]);

        $post->update($validatedData);

        if ($post->banner->id !== $validatedData['banner']) {
            $post->banner()->delete();
            $post->banner()->sync($validatedData['banner']);
        }

        if ($post->status->id !== $validatedData['status']) {
            $post->status()->sync($validatedData['status']);
            event(new PostStatusUpdated($post->refresh()));
        }

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function slugs(): JsonResponse
    {
        return response()->json(Post::pluck('slug')->transform(function ($item, $key) {
            return ['params' => ['slug' => $item]];
        }));
    }

    public function recent(Request $request): PostCollection
    {
        $validatedData = $request->validate(['cursor' => 'numeric|integer', 'perPage' => 'numeric|integer|max:30']);
        $perPage = $validatedData['perPage'] ?? null;
        $cursor = $validatedData['cursor'] ?? null;

        $postCollection = new PostCollection(Post::published()->latest()->when(isset($cursor), function ($query) use ($cursor) {
            $query->where('id', '<=', $cursor);
        })->simplePaginate($perPage)->appends(['cursor' => $cursor]));

        if (!isset($cursor)) $cursor = $postCollection->collection->first()->id ?? null;

        return ($postCollection)->additional(['meta' => ['cursor' => $cursor]]);
    }
}
