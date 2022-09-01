<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function index(Request $request, string $articleTypeName): ArticleCollection
    {
        $this->authorize('viewAny', Article::class);
        $validatedData = $request->validate([
            'page'          => 'integer',
            'perPage'       => 'integer|min:1|max:100',
            'sortBy'        => 'string|in:id,title,created_at,updated_at,published_at,slug',
            'sortDirection' => 'string|in:asc,desc',
        ]);

        /* Get ArticleType id by name */
        $articleTypes = Cache::remember('articleTypes', null, function () {
            return ArticleType::all();
        });
        $articleTypeId = $articleTypes->where('name', $articleTypeName)
            ->firstOrFail()->id;


        /* Pagination parameters */
        $perPage = $validatedData['perPage'] ?? 100;
        $sortBy = $validatedData['sortBy'] ?? 'id';
        $sortDirection = $validatedData['sortDirection'] ?? 'desc';


        return new ArticleCollection(Article::with(['author'])
            ->where('article_type_id', $articleTypeId)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Article::class);
        $validatedData = $request->validate([
            'title'             => ['string', 'max:255', 'required'],
            'excerpt'           => ['string', 'required'],
            'markdown'          => ['string', 'required'],
            'published'         => ['boolean', 'required'],
            'article_banner_id' => ['integer', 'required', 'exists:files,id'],
        ]);

        /** @var Article $article */
        $article = $request->user()
            ->articles()
            ->create($validatedData);
        $article->banner()
            ->sync([$validatedData['article_banner_id']]);

        if ($validatedData['published']) {
            $article->publish();
            return response()->json(['message' => 'Article created and published successfully.'], Response::HTTP_CREATED);
        }

        return response()->json(['message' => 'Article created successfully.'], Response::HTTP_CREATED);
    }

    public function show(Request $request, string $articleTypeName, string $articleSlug): ArticleResource
    {
        $article = Article::with(['banner', 'author'])
            ->where('slug', $articleSlug)
            ->firstOrFail();

        $this->authorize('view', $article);
        return new ArticleResource($article);
    }

    public function update(Request $request, string $articleTypeName, Article $article): JsonResponse
    {
        $this->authorize('update', $article);
        $validatedData = $request->validate([
            'title'             => ['string', 'max:255', 'required'],
            'excerpt'           => ['string', 'required'],
            'markdown'          => ['string', 'required'],
            'published'         => ['boolean', 'required'],
            'article_banner_id' => ['integer', 'required'],
        ]);

        $article->update($validatedData);
        $article->banner()
            ->sync([$validatedData['article_banner_id']]);

        if (! $article->published_at && $validatedData['published']) $article->publish();
        if ($article->published_at && ! $validatedData['published']) $article->unPublish();

        return response()->json(['message' => 'Article updated successfully.'], Response::HTTP_OK);
    }

    public function destroy(ArticleType $articleType, Article $article): JsonResponse
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully.'], Response::HTTP_OK);
    }

    public function recent(Request $request, ArticleType $articleType): ArticleCollection
    {
        $cachedUrls = Cache::get('recent-article-urls', []);
        if (! in_array($request->fullUrl(), $cachedUrls)) {
            Cache::put('recent-article-urls', [...$cachedUrls, $request->fullUrl()]);
        }

        return
            Cache::rememberForever($request->fullUrl(), fn() => new ArticleCollection(Article::with([
                'author',
                'banner',
            ])->published()
                ->latest()
                ->whereBelongsTo($articleType, 'articleType')
                ->cursorPaginate(10)));
    }

    public function slugs(): Collection
    {
        return Article::published()
            ->pluck('slug')
            ->map(fn($slug) => [
                'params' => [
                    'slug' => $slug,
                ],
            ]);
    }
}
