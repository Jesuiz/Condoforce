<?php
namespace App\Policies;

use App\Models\Condominium;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CondominiumPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Condominium $condominium)
    {
        if ($condominium->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished condominium')) {
            return true;
        }

        // authors can view their own unpublished condominium
        return $user->id == $condominium->user_id;
    }

    public function create(User $user)
    {
        return ($user->can('create condominium'));
    }

    public function update(User $user, Condominium $condominium)
    {
        if ($user->can('edit all condominium')) {
            return true;
        }

        if ($user->can('edit own condominium')) {
            return $user->id == $condominium->user_id;
        }
    }

    public function delete(User $user, Condominium $condominium)
    {
        if ($user->can('delete any condominium')) {
            return true;
        }

        if ($user->can('delete own condominium')) {
            return $user->id == $condominium->user_id;
        }
    }
}