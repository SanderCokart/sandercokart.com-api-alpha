<?php

namespace App\Contracts;

use App\Enums\DisksEnum;
use Illuminate\Http\UploadedFile;

interface FileUploadServiceContract
{
    public function handleFileUpload(UploadedFile $file, string $folderName, DisksEnum $disk = DisksEnum::PRIVATE): string;
}
