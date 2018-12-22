<?php

namespace App\Policies;

use App\Models\Feed;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class FeedPolicy
{
    use HandlesAuthorization;
    
    public function index(User $user, $request)
    {
        return $user->id === $request->user()->id;
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
    public function create(User $user, Request $request)
    {
        $feedOk = true;
        $folderOk = true;
        $feedUserId = array_get($request->get('data'), 'relationships.user.data.id');
        $folderId = array_get($request->get('data'), 'relationships.folder.data.id');
        
        if ($feedUserId) {
            $feedOk = $user->id === $feedUserId;
        }
        
        if ($folderId) {
            $folder = Folder::find($folderId);
            $folderUserId = $folder->user->id;
            $folderOk = $user->id === $folderUserId;
        }
        
        return ($feedOk && $folderOk);
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
