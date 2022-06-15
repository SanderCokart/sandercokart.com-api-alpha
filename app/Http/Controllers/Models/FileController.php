<?php

namespace App\Http\Controllers\Models;

use App\Enums\ArticleType;
use App\Enums\DisksEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return FileResource::collection(File::all());
    }

    public function store(Request $request, FileUploadService $fileUploadService): FileResource
    {
        $mimeType = $request->file('file')->getMimeType();

        if (! $mimeType) abort(422, 'Could not process file.');

        if (Str::contains($mimeType, 'image')) {
            $validatedData = $request->validate(['file' => 'file|image|required|max:50000']);
        } else {
            $validatedData = $request->validate(['file' => 'file|required|max:50000']);
        }

        $relativePath = $fileUploadService->handleFileUpload(
            $validatedData['file'],
            $mimeType,
            DisksEnum::PRIVATE
        );

        return new FileResource(File::create([
            'relative_path' => $relativePath,
            'is_private' => true,
        ]));
    }

    public function show(Request $request, File $file): BinaryFileResponse
    {
        $this->authorize('view', $file);
        return response()->download(Storage::disk('private')->path($file->relative_path));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy(Request $request, File $file): JsonResponse
    {
        $file->delete();
        return response()->json(['message' => 'File deleted.']);
    }
}
