<?php

namespace App\Services;

use App\Contracts\FileUploadServiceContract;
use App\Enums\ArticleType;
use App\Enums\VisibilityEnum;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class FileUploadService implements FileUploadServiceContract
{
    /**@return string relative path */
    public function handleFileUpload(UploadedFile $file, string $mimeType, ?ArticleType $disk = ArticleType::PRIVATE): string
    {
        $relativePath = $file->store($this->determineFolderName($mimeType), ['disk' => $disk->value]);

        if (! $relativePath) {
            return abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'File upload failed.');
        }
        return $relativePath;
    }

    public function determineFolderName(string $mimeType): string
    {
        return 'uploads/' . $mimeType;
    }
}
