<?php

namespace App\Contracts;

use App\Enums\ArticleType;
use App\Enums\DisksEnum;
use Illuminate\Http\UploadedFile;

interface FileUploadServiceContract
{
    public function handleFileUpload(UploadedFile $file, string $mimeType, DisksEnum $disk = DisksEnum::PRIVATE): string;


    public function determineFolderName(string $mimeType): string;
}
