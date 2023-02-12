<?php

namespace App\Traits;

use App\Models\Groupable;
use App\Models\GroupableMember;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| CanJoinGroups
|--------------------------------------------------------------------------
|
| Use this trait in your user model in order to provide group
| functionality to your users.
|
*/

trait JoinsGroups
{
    /**
     * User leave a group.
     *
     * @param $group
     *
     * @return int
     */
    public function leaveGroup($group)
    {
        DB::table('groupable_members')->where([
            ['group_id', '=', $group->id],
            ['group_type', '=', get_class($group)],
            ['user_id', '=', $this->id],
        ])->delete();

        // Check if user have some roles
        return DB::table('groupable_roles')->where([
            ['group_id', '=', $group->id],
            ['group_type', '=', get_class($group)],
            ['user_id', '=', $this->id],
        ])->delete();
    }

    /**
     * Retrieve the roles a user has within a given group.
     * API: $user->groupRoles($group, $role).
     *
     * @param $group
     *
     * @return array
     *
     * @throws Exception
     */
    public function groupRoles($group)
    {
        $collection = collect([]);

        if ($this->belongsToGroup($group)) {
            $roles = DB::table('groupable_roles')->where([
                'group_id' => $group->id,
                'group_type' => get_class($group),
                'user_id' => $this->id,
            ])->get();

            foreach ($roles as $role) {
                $collection->push($role->role);
            }

            return $collection;
        } else {
            throw new Exception('User is not a member of this group.');
        }
    }

    /**
     * Check if a user belongs to a particular group.
     * API: $user->belongsToGroup($group).
     *
     * @return bool
     */
    public function belongsToGroup($group)
    {
        $check = DB::table('groupable_members')->where([
            ['group_id', '=', $group->id],
            ['group_type', '=', get_class($group)],
            ['user_id', '=', $this->id],
        ])->get();

        if ($check->count() > 0) {
            return true;
        }

        return false;
    }

    public function groupableMembers()
    {
        return $this->hasMany('groupable_members');
    }

    /**
     * Get all user's groups.
     * API: $user->groups().
     *
     * @param null $types
     * @param bool $asQuery
     *
     * @return Builder|Collection
     */
    public function groups($types = null, $asQuery = false)
    {
        $collection = collect([]);

        $groups = GroupableMember::query()->where([
            ['user_id', '=', $this->id],
        ]);

        if ($asQuery) {
            return $groups;
        }

        $groups = $groups->get();

        foreach ($groups as $group) {
            if ($types) {
                if (in_array($group->group_type, $types, true)) {
                    $model = Groupable::resolveModel($group->group_type, $group->group_id);

                    if (!$model) {
                        $group->forceDelete();
                    }

                    $collection->push($model);
                }
            } else {
                $model = Groupable::resolveModel($group->group_type, $group->group_id);
                if (!$model) {
                    $group->forceDelete();
                }
                $collection->push(Groupable::resolveModel($group->group_type, $group->group_id));
            }
        }

        return collect($collection)->filter();
    }

    /**
     * Check if a user has a group role.
     * API: $user->hasGroupRole($group, $role).
     *
     * @return bool
     */
    public function hasGroupRole($group, $role)
    {
        $check = DB::table('groupable_roles')->where([
            'group_id' => $group->id,
            'group_type' => get_class($group),
            'user_id' => $this->id,
            'role' => $role,
        ])->get();

        if ($check->count() > 0) {
            return true;
        }

        return false;
    }

    public function scopeInGroup($query, $model)
    {
        /*
         * @var $query Builder
         */
        return $query->join('groupable_members', 'users.id', '=', 'groupable_members.user_id')
                     ->where('groupable_members.group_id', $model->id)
                     ->where('groupable_members.group_type', get_class($model))
                     ->distinct();
    }
}
