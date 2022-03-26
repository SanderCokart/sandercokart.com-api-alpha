<?php

namespace App\Console\Commands;

use App\Models\ArticleBanner;
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
        $urls = ArticleBanner::pluck('relative_url')->toArray();
        $allFiles = Storage::disk('private')->files('/uploads/models/ArticleBanner');
        $differences = array_diff($allFiles, $urls);
        foreach ($differences as $difference) {
            Storage::disk('private')->delete($difference);
            Storage::disk('public')->delete($difference);
        }

        return 'Done';
    }
}
