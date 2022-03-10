<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string $articleTypeName
     * @return ArticleCollection
     */
    public function index(Request $request, string $articleTypeName): ArticleCollection
    {
        $validatedData = $request->validate([
            'page' => 'integer',
            'perPage' => 'integer|min:1|max:100',
            'sortBy' => 'string|in:id,title,created_at,updated_at,published_at,slug',
            'sortDirection' => 'string|in:asc,desc',
        ]);

        /* Get ArticleType id by name */
        $articleTypes = Cache::remember('articleTypes', 0, function () {
            return ArticleType::all();
        });
        $articleTypeId = $articleTypes->where('name', $articleTypeName)->firstOrFail()->id;


        /* Pagination parameters */
        $perPage = $validatedData['perPage'] ?? 100;
        $sortBy = $validatedData['sortBy'] ?? 'id';
        $sortDirection = $validatedData['sortDirection'] ?? 'desc';

        /* Cache URLs */
        $cachedUrls = Cache::get('article-urls', []);
        if (!in_array($request->fullUrl(), $cachedUrls)) {
            Cache::put('article-urls', [...$cachedUrls, $request->fullUrl()]);
        }

        return Cache::remember($request->fullUrl(), null, function () use ($articleTypeId, $perPage, $sortBy, $sortDirection) {
            return new ArticleCollection(
                Article::where('article_type_id', $articleTypeId)
                    ->orderBy($sortBy, $sortDirection)
                    ->paginate($perPage)
                    ->withQueryString()
            );
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Article::class);
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'markdown' => ['string', 'required'],
            'article_banner_id' => ['integer', 'required', 'exists:files,id'],
            'article_type_id' => ['integer', 'required', 'exists:article_types,id'],
        ]);

        $request->user()->articles()->create($validatedData);

        return response()->json(['message' => 'Article created successfully.'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param ArticleType $articleType
     * @param Article $article
     * @return ArticleResource
     * @throws AuthorizationException
     */
    public function show(Request $request, ArticleType $articleType, Article $article): ArticleResource
    {
        $this->authorize('view', $article);
        return new ArticleResource($article);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'banner' => ['integer', 'required'],
            'markdown' => ['string', 'required'],
            'status' => ['integer', 'required'],
            'publish' => ['boolean', 'required'],
        ]);

        $article->update($validatedData);

        if (!$article->published_at && $validatedData['publish']) $article->publish();
        if ($article->published_at && !$validatedData['publish']) $article->unPublish();


        if ($article->banner->id !== $validatedData['banner']) {
            $article->banner()->save($validatedData['banner']);
        }

        return response()->json(['message' => 'Article updated successfully.'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleType $articleType
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(ArticleType $articleType, Article $article): JsonResponse
    {
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully.'], Response::HTTP_OK);
    }

    public function recent(Request $request, ArticleType $articleType): ArticleCollection
    {
        $request->validate([
            'articleType' => 'string|in:posts,tips-&-tutorials'
        ]);

        return new ArticleCollection(Article::with(['author', 'banner'])
            ->whereBelongsTo($articleType, 'articleType')
            ->latest()
            ->cursorPaginate(10));
    }
}
