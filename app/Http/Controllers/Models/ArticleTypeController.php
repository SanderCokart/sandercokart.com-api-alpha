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
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(ArticleType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        //validate incoming request
        $validatedData = $this->validate($request, [
            'name' => 'required|string',
        ]);

        ArticleType::create($validatedData);

        return response('ArticleType Created successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param ArticleType $articleType
     * @return JsonResponse
     */
    public function show(ArticleType $articleType): JsonResponse
    {
        return response()->json($articleType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ArticleType $articleType
     * @return Response
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

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleType $articleType
     * @return void
     */
    public function destroy(ArticleType $articleType)
    {
        $articleType->delete();
        response()->noContent();
    }
}
