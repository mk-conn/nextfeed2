<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ArticleAction;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleActionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the article action.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleAction  $articleAction
     * @return mixed
     */
    public function view(User $user, ArticleAction $articleAction)
    {
        //
    }

    /**
     * Determine whether the user can create article actions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the article action.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleAction  $articleAction
     * @return mixed
     */
    public function update(User $user, ArticleAction $articleAction)
    {
        //
    }

    /**
     * Determine whether the user can delete the article action.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleAction  $articleAction
     * @return mixed
     */
    public function delete(User $user, ArticleAction $articleAction)
    {
        //
    }

    /**
     * Determine whether the user can restore the article action.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleAction  $articleAction
     * @return mixed
     */
    public function restore(User $user, ArticleAction $articleAction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the article action.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ArticleAction  $articleAction
     * @return mixed
     */
    public function forceDelete(User $user, ArticleAction $articleAction)
    {
        //
    }
}
