<?php

namespace App\Contracts;

use App\Enums\ArticleType;
use Illuminate\Http\UploadedFile;

interface FileUploadServiceContract
{
    public function handleFileUpload(UploadedFile $file, string $mimeType, ?ArticleType $disk = ArticleType::PRIVATE): string;


    public function determineFolderName(string $mimeType): string;
}
