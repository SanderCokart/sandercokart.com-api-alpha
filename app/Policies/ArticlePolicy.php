<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Article $article
     * @return bool
     */
    public function view(?User $user, Article $article): bool
    {

        //if the user is a user or guest, he can view only published articles
        if (!$user || !$user->isAdmin()) {
            return $article->isPublished();
        }

        //if the user is an admin, he can view all articles
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return$user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function update(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->roles->contains(Role::ADMIN);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->roles->contains(Role::ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->roles->contains(Role::ADMIN);
    }
}
