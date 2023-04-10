<?php

namespace App\Policies;

use App\Models\Capture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CapturePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;
                
            case "Self":
                return true;
                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Capture  $capture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Capture $capture)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $capture->user_id;

            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return true;

            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Capture  $capture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Capture $capture)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $capture->user_id;

            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Capture  $capture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Capture $capture)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $capture->user_id;

            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Capture  $capture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Capture $capture)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $capture->user_id;

            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Capture  $capture
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Capture $capture)
    {
        switch ($user->capture_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $capture->user_id;

            case "None":
            default:
                return false;
        }
    }
}
