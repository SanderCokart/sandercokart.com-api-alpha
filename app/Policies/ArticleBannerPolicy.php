<?php

namespace App\Policies;

use App\Models\ArticleBanner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleBannerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ArticleBanner $articleBanner): bool
    {
        return $user->isAdmin() || $articleBanner->article->isPublished();
    }

    public function create(User $user): bool
    {

    }
}
