<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;


    public function index(User $user, $request)
    {
        return true;
    }

    /**
     * Determine whether the user can create folders.
     *
     * @param  \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the folder.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Folder $folder
     *
     * @return mixed
     */
    public function delete(User $user, Folder $folder)
    {
        return $this->update($user, $folder);
    }

    /**
     * Determine whether the user can update the folder.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Folder $folder
     *
     * @return mixed
     */
    public function update(User $user, Folder $folder)
    {
        return $this->view($user, $folder);
    }

    /**
     * Determine whether the user can view the folder.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Folder $folder
     *
     * @return mixed
     */
    public function view(User $user, Folder $folder)
    {
        return ($user->id === $folder->user->id);
    }

    /**
     * Determine whether the user can restore the folder.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Folder $folder
     *
     * @return mixed
     */
    public function restore(User $user, Folder $folder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the folder.
     *
     * @param  \App\Models\User   $user
     * @param  \App\Models\Folder $folder
     *
     * @return mixed
     */
    public function forceDelete(User $user, Folder $folder)
    {
        //
    }
}
