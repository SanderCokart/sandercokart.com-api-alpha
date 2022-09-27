<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Article   $article
     *
     * @return bool
     */
    public function view(?User $user, Article $article): bool
    {
        return $article->isPublished() || $user?->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function create(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function update(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }
}
