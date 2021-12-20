<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\Http\Controllers\strtr;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return FileResource::collection(File::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return FileResource
     */
    public function store(Request $request): FileResource
    {
        $validatedData = $request->validate(['file' => 'image|required|max:10000']);

        $vars = ['$name' => uniqid(), '$ext' => $validatedData['file']->getClientOriginalExtension()];
        $newName = strtr('$name.$ext', $vars);

        $relativePath = Storage::disk('private')->putFileAs('uploads', $validatedData['file'], $newName);

        return new FileResource(File::create([
            'original_name' => $validatedData['file']->getClientOriginalName(),
            'mime_type' => $validatedData['file']->getMimeType(),
            'relative_url' => $relativePath,
            'is_private' => true,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param File $file
     * @return StreamedResponse
     */
    public function show(Request $request, File $file): StreamedResponse
    {
        return Storage::disk('private')->response($file->relative_url);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param File $file
     * @return bool
     */
    public function destroy(Request $request, File $file): bool
    {
        return $file->delete();
    }
}
