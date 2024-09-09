<?php
namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Report $report)
    {
        if ($report->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished report')) {
            return true;
        }

        // authors can view their own unpublished report
        return $user->id == $report->user_id;
    }

    public function create(User $user)
    {
        return ($user->can('create report'));
    }

    public function update(User $user, Report $report)
    {
        if ($user->can('edit all report')) {
            return true;
        }

        if ($user->can('edit own report')) {
            return $user->id == $report->user_id;
        }
    }

    public function delete(User $user, Report $report)
    {
        if ($user->can('delete any report')) {
            return true;
        }

        if ($user->can('delete own report')) {
            return $user->id == $report->user_id;
        }
    }
}