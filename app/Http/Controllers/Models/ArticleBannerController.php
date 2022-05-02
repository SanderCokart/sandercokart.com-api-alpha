<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleBannerCollection;
use App\Http\Resources\ArticleBannerResource;
use App\Models\ArticleBanner;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArticleBannerController extends Controller
{
    public function index(): ArticleBannerCollection
    {
        $this->authorize('viewAny', ArticleBanner::class);
        return new ArticleBannerCollection(ArticleBanner::all());
    }

    public function store(Request $request)
    {

    }

    public function show(ArticleBanner $articleBanner): StreamedResponse
    {
        $this->authorize('view', $articleBanner);
        return Storage::disk('private')->response($articleBanner->relative_url);
    }
}
