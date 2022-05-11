<?php

namespace App\Providers;

use App\Contracts\FileUploadServiceContract;
use App\Services\FileUploadService;
use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(FileUploadServiceContract::class, FileUploadService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
