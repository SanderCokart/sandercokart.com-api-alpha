<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\ArticleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ArticleTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', ArticleType::class);
        return response()->json(ArticleType::all());
    }

    public function store(Request $request): Response
    {
        $this->authorize('create', ArticleType::class);
        $validatedData = $this->validate($request, [
            'name' => 'required|string',
        ]);

        ArticleType::create($validatedData);

        return response('ArticleType Created successfully', 201);
    }

    public function show(ArticleType $articleType): JsonResponse
    {
        $this->authorize('view', $articleType);
        return response()->json($articleType);
    }

    public function update(Request $request, ArticleType $articleType): Response
    {
        $this->authorize('update', $articleType);
        $validatedData = $this->validate($request, [
            'name' => 'required|string',
        ]);

        $articleType->update($validatedData);

        return response('ArticleType Updated successfully', 200);
    }

    public function destroy(ArticleType $articleType)
    {
        $this->authorize('delete', $articleType);
        $articleType->delete();
        response()->noContent();
    }
}
