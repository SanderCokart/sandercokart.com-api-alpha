<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ArticleCollection
     * @throws Exception
     */
    public function index(Request $request, ArticleType $articleType): ArticleCollection
    {
        $validatedData = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'sortBy' => 'string|in:id,title,created_at,updated_at',
            'sortDirection' => 'string|in:asc,desc',
            'articleType' => 'string|in:' . implode(',', ArticleType::pluck('name')->toArray()),
        ]);

        $perPage = $validatedData['perPage'] ?? 100;
        $sortBy = $validatedData['sortBy'] ?? 'id';
        $sortDirection = $validatedData['sortDirection'] ?? 'desc';

        return new ArticleCollection(
            Article::with(['user', 'statuses', 'banner'])
                ->where('article_type_id', $articleType->id)
                ->orderBy($sortBy, $sortDirection)
                ->paginate($perPage)
                ->withQueryString()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArticleRequest $request
     * @return Response
     */
    public function store(StoreArticleRequest $request): Response
    {
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'banner' => ['integer', 'required'],
            'markdown' => ['string', 'required'],
            'status' => ['integer', 'required'],
        ]);

        $article = $request->user()->articles()->create($validatedData);
        $article->statuses()->sync($validatedData['status']);
        $article->banner()->sync($validatedData['banner']);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param ArticleType $articleType
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Request $request, ArticleType $articleType, Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArticleRequest $request
     * @param Article $article
     * @return Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'banner' => ['integer', 'required'],
            'markdown' => ['string', 'required'],
            'status' => ['integer', 'required'],
        ]);

        $article->update($validatedData);

        if ($article->status->id !== $validatedData['status']) {
            $article->statuses()->sync($validatedData['status']);
            $article->togglePrivacy();
        }

        if ($article->banner->id !== $validatedData['banner']) {
            $article->banner()->save($validatedData['banner']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function recent(Request $request, ArticleType $articleType): ArticleCollection
    {
        return new ArticleCollection(Article::with(['user', 'statuses', 'banner'])
            ->published()
            ->whereBelongsTo($articleType)
            ->orderBy('id', 'desc')
            ->cursorPaginate(5));
    }
}
