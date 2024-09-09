<?php
namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Inventory $inventory)
    {
        if ($inventory->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished inventory')) {
            return true;
        }

        // authors can view their own unpublished inventory
        return $user->id == $inventory->user_id;
    }

    public function create(User $user)
    {
        return ($user->can('create inventory'));
    }

    public function update(User $user, Inventory $inventory)
    {
        if ($user->can('edit all inventory')) {
            return true;
        }

        if ($user->can('edit own inventory')) {
            return $user->id == $inventory->user_id;
        }
    }

    public function delete(User $user, Inventory $inventory)
    {
        if ($user->can('delete any inventory')) {
            return true;
        }

        if ($user->can('delete own inventory')) {
            return $user->id == $inventory->user_id;
        }
    }
}