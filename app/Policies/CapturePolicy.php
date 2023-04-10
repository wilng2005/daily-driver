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

        // All of these need to return true for the user to be able to view the resource index in Nova
        // To prevent users from viewing resources that are not their own, the index query is filtered in the Nova resource
        

        switch ($user->capture_resource_access) {
            case "All":
                //@codeCoverageIgnoreStart
                return true;
                //@codeCoverageIgnoreEnd
                
            case "Self":
                //@codeCoverageIgnoreStart
                return true;
                //@codeCoverageIgnoreEnd
                
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
     * 
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * 
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * 
     * @codeCoverageIgnore
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
     * 
     * @codeCoverageIgnore
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
