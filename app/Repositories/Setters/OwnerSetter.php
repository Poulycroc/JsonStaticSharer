<?php

namespace App\Repositories\Setters;

use App\Models\Project;
use App\Models\User;

/**
 * Common trait to define an OwnerSetter.
 */
trait OwnerSetter
{
    /**
     * Set current user as admin/owner of the entities.
     *
     * @param $model JoinsGroups|GroupMembers|GroupRoles
     */
    public static function setOwner($model, User $user)
    {
        $model->join($user);
        // $model->grant($user, User::ROLE_ADMIN);
        // $model->grant($user, User::ROLE_CREATOR);
    }

    public static function setProjectCreator(Project $project, User $user)
    {
        $project->join($user);
        $project->grant($user, 'creator');
        // $project->setPermissionByRole(Campaign::ROLE_AUTHOR);
    } 
}
