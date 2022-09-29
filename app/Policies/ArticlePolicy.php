<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(?User $user, Article $article): bool
    {
        return $article->isPublished() || $user?->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }
}
