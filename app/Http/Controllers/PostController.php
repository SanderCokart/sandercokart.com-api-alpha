<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
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
        $perPage = $validatedData['perPage'] ?? 5;
        $cursor = $validatedData['cursor'] ?? null;

        $postCollection = new PostCollection(Post::latest()->when(isset($cursor), function ($query) use ($cursor) {
            $query->where('id', '<=', $cursor);
        })->simplePaginate($perPage)->appends(['cursor' => $cursor]));

        if (!isset($cursor)) $cursor = $postCollection->collection->first()->id;

        return ($postCollection)->additional(['meta' => ['cursor' => $cursor]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Post $post)
    {
        //
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
}
