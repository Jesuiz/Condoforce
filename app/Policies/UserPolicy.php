<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(?User $user)
    {
        if ($user->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished user')) {
            return true;
        }

        // authors can view their own unpublished user
        return $user->id == $user->user_id;
    }

    public function create(User $user)
    {
        return ($user->can('create user'));
    }

    public function update(User $user)
    {
        if ($user->can('edit all user')) {
            return true;
        }

        if ($user->can('edit own user')) {
            return $user->id == $user->user_id;
        }
    }

    public function delete(User $user)
    {
        if ($user->can('delete any user')) {
            return true;
        }

        if ($user->can('delete own user')) {
            return $user->id == $user->user_id;
        }
    }
}