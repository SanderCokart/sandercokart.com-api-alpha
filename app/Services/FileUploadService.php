<?php

namespace App\Services;

use App\Enums\DisksEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function handleFileUpload(UploadedFile $file, string $folderName = '/uploads', DisksEnum $disk = DisksEnum::PRIVATE): string
    {
        $relativePath = $file->store($folderName, ['disk' => $disk()]);

        if (! $relativePath) {
            return abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'File upload failed.');
        }

        return $relativePath;
    }
}
