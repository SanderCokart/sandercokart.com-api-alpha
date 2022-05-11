<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneFiles extends Command
{

    protected $signature = 'prune:files';
    protected $description = 'Prune all files that are not attached to a file model';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $privateFiles = Storage::disk('private')->allFiles('uploads');
        $publicFiles = Storage::disk('public')->allFiles('uploads');
        $allFiles = array_merge($privateFiles, $publicFiles);

        $attachedFiles = Article::has('banner')->get()->map(function ($article) {
            return $article->banner->relative_path;
        })->toArray();

        $diff = array_diff($allFiles, $attachedFiles);

        foreach ($diff as $file) {
            Storage::disk('private')->delete($file);
            Storage::disk('public')->delete($file);
        }

        $this->info('Files pruned');
    }
}
