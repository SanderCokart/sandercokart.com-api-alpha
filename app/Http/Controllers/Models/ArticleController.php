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
        $articleTypes = Cache::remember('articleTypes', null, function () {
            return ArticleType::all();
        });
        $articleTypeId = $articleTypes->where('name', $articleTypeName)->firstOrFail()->id;


        /* Pagination parameters */
        $perPage = $validatedData['perPage'] ?? 100;
        $sortBy = $validatedData['sortBy'] ?? 'id';
        $sortDirection = $validatedData['sortDirection'] ?? 'desc';

        /* Cache URLs */
        $cachedUrls = Cache::get('article-urls', []);
        $generatedCacheUrl = $request->fullUrl() . '&canViewAll=' . !!$request->user()?->can('viewAll', Article::class);
        if (!in_array($generatedCacheUrl, $cachedUrls)) {
            Cache::put('article-urls', [...$cachedUrls, $request->fullUrl()]);
        }

        return Cache::remember($generatedCacheUrl, null, function () use ($request, $articleTypeId, $perPage, $sortBy, $sortDirection) {
            return new ArticleCollection(
                Article::where('article_type_id', $articleTypeId)
                    ->when(!$request->user()?->can('viewAll', Article::class), fn($query) => $query->published())
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
            'article_banner_id' => ['integer', 'required', 'exists:article_banners,id'],
            'article_type_id' => ['integer', 'required', 'exists:article_types,id'],
            'published' => ['boolean', 'required'],
        ]);

        $article = $request->user()->articles()->create($validatedData);

        if ($validatedData['published']) {
            $article->publish();
            return response()->json(['message' => 'Article created and published successfully.'], Response::HTTP_CREATED);
        }

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
     * @throws AuthorizationException
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $this->authorize('update', $article);
        $validatedData = $request->validate([
            'title' => ['string', 'max:255', 'required'],
            'excerpt' => ['string', 'required'],
            'markdown' => ['string', 'required'],
            'article_banner_id' => ['integer', 'required', 'exists:article_banners,id'],
            'article_type_id' => ['integer', 'required', 'exists:article_types,id'],
            'published' => ['boolean', 'required'],
        ]);

        $article->update($validatedData);

        if (!$article->published_at && $validatedData['published']) $article->publish();
        if ($article->published_at && !$validatedData['published']) $article->unPublish();

        return response()->json(['message' => 'Article updated successfully.'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleType $articleType
     * @param Article $article
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(ArticleType $articleType, Article $article): JsonResponse
    {
        $this->authorize('delete', $article);
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
