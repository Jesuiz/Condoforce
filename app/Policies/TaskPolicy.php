<?php
namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Task $task)
    {
        if ($task->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished task')) {
            return true;
        }

        // authors can view their own unpublished task
        return $user->id == $task->user_id;
    }

    public function create(User $user)
    {
        return ($user->can('create task'));
    }

    public function update(User $user, Task $task)
    {
        if ($user->can('edit all task')) {
            return true;
        }

        if ($user->can('edit own task')) {
            return $user->id == $task->user_id;
        }
    }

    public function delete(User $user, Task $task)
    {
        if ($user->can('delete any task')) {
            return true;
        }

        if ($user->can('delete own task')) {
            return $user->id == $task->user_id;
        }
    }
}