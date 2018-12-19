<?php

namespace App\Policies;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedPolicy
{
    use HandlesAuthorization;

    public function index(User $user, $request)
    {

    }

    /**
     * Determine whether the user can view the feed.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Feed $feed
     *
     * @return mixed
     */
    public function view(User $user, Feed $feed)
    {
        return ($user->id === $feed->user->id);
    }

    /**
     * Determine whether the user can create feeds.
     *
     * @param  \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the feed.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Feed $feed
     *
     * @return mixed
     */
    public function update(User $user, Feed $feed)
    {
        return ($user->id === $feed->user->id);
    }

    /**
     * Determine whether the user can delete the feed.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Feed $feed
     *
     * @return mixed
     */
    public function delete(User $user, Feed $feed)
    {
        return ($user->id === $feed->user->id);
    }

    /**
     * Determine whether the user can restore the feed.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Feed $feed
     *
     * @return mixed
     */
    public function restore(User $user, Feed $feed)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the feed.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Feed $feed
     *
     * @return mixed
     */
    public function forceDelete(User $user, Feed $feed)
    {
        //
    }
}
