<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ArticleCollection
     * @throws Exception
     */
    public function index(Request $request): ArticleCollection
    {

        $articleTypes = Cache::get('article_types', function () {
            $result = ArticleType::pluck('name')->toArray();
            Cache::put('article_types', $result);
            return $result;
        });

        dd($articleTypes);

        $validatedData = $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'sort_by' => 'string|in:id,title,created_at,updated_at',
            'sort_direction' => 'string|in:asc,desc',
            'article_type' => 'string|in:' . implode(',', ArticleType::pluck('name')->toArray()),
        ]);

        //set default values
        $page = $validatedData['page'] ?? 1;
        $perPage = $validatedData['per_page'] ?? 100;
        $sortBy = $validatedData['sort_by'] ?? 'created_at';
        $sortDirection = $validatedData['sort_direction'] ?? 'desc';

        return new ArticleCollection(Article::with('statuses', 'articleType', 'user')->orderBy($sortBy, $sortDirection)->cursorPaginate($perPage));
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
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article, ArticleType $articleType): ArticleResource
    {
        dd($articleType);
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
}
