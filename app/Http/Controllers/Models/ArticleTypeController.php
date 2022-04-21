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
        return response()->json(ArticleType::all());
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        $validatedData = $this->validate($request, [
            'name' => 'required|string',
        ]);

        ArticleType::create($validatedData);

        return response('ArticleType Created successfully', 201);
    }

    public function show(ArticleType $articleType): JsonResponse
    {
        return response()->json($articleType);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, ArticleType $articleType): Response
    {
        $validatedData = $this->validate($request, [
            'name' => 'required|string',
        ]);

        $articleType->update($validatedData);

        return response('ArticleType Updated successfully', 200);
    }

    public function destroy(ArticleType $articleType)
    {
        $articleType->delete();
        response()->noContent();
    }
}
