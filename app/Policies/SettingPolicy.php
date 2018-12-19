<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $setting
     * @return mixed
     */
    public function view(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can create settings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $setting
     * @return mixed
     */
    public function update(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can delete the setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $setting
     * @return mixed
     */
    public function delete(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can restore the setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $setting
     * @return mixed
     */
    public function restore(User $user, Setting $setting)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the setting.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Setting  $setting
     * @return mixed
     */
    public function forceDelete(User $user, Setting $setting)
    {
        //
    }
}
