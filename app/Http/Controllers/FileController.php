<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|File[]
     */
    public function index()
    {
        return File::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return File|Model
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(['file' => 'image|required|max:10000']);

        $vars = ['$name' => uniqid(), '$ext' => $validatedData['file']->getClientOriginalExtension()];
        $newName = strtr('$name.$ext', $vars);

        $relativePath = Storage::disk('private')->putFileAs('uploads', $validatedData['file'], $newName);

        return File::create([
            'original_name' => $validatedData['file']->getFilename(),
            'mime_type' => $validatedData['file']->getMimeType(),
            'relative_path' => $relativePath,
        ]);
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
        return Storage::disk('private')->response($file->relative_path);
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
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
