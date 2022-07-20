<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleType;
use App\Models\EmailVerification;
use App\Models\File;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class TruncateAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::truncate();
        ArticleType::truncate();
        Article::truncate();
        File::truncate();
        EmailVerification::truncate();
        Role::truncate();

        return 'All tables truncated';
    }
}
