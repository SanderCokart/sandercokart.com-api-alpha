<?php

namespace App\Policies;

use App\Models\ArticleType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ArticleType $articleType): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ArticleType $articleType): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ArticleType $articleType): bool
    {
        return $user->isAdmin();
    }
}
