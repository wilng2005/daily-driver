<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function viewAny(User $user)
    {
        switch ($user->user_resource_access) {
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
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function view(User $user, User $model)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $model->user_id;

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
     * 
     * @codeCoverageIgnore
     */
    public function create(User $user)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function update(User $user, User $model)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $model->user_id;
                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function delete(User $user, User $model)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $model->user_id;
                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function restore(User $user, User $model)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $model->user_id;
                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     * 
     * @codeCoverageIgnore
     */
    public function forceDelete(User $user, User $model)
    {
        switch ($user->user_resource_access) {
            case "All":
                return true;

            case "Self":
                return $user->id == $model->user_id;
                
            case "None":
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can upload files.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function uploadFiles(User $user)
    {
        return true;
    }
    
}
