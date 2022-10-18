<?php

namespace App\Services;

use App\Enums\DisksEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use ReflectionClass;
use ReflectionException;

class FileUploadService
{
    /**
     * @throws ReflectionException
     */
    public static function determineFolderName(Model|string $model): string
    {
        $rc = new ReflectionClass($model);

        return 'uploads/' . $rc->getShortName();
    }

    public function handleFileUpload(UploadedFile $file, Model|string $model, DisksEnum $disk = DisksEnum::PRIVATE): string
    {
        $relativePath = $file->store(self::determineFolderName($model), ['disk' => $disk()]);

        if (! $relativePath) {
            return abort(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'File upload failed.');
        }
        return $relativePath;
    }
}
