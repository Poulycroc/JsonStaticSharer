<?php

namespace App\Traits;

use App\Models\GroupableMember;
use App\Models\User;
use App\Traits\Utils\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait GroupMembers
{
    /**
     * Join a group
     * API: $group->join($user).
     *
     * @return bool
     */
    public function join($user)
    {
        return DB::table('groupable_members')->insert([
            'group_id' => $this->id,
            'group_type' => get_class($this),
            'user_id' => $user->id,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public function groupable_members()
    {
        return $this->hasManyThrough(User::class, GroupableMember::class, 'user_id', 'id', 'id', 'group_id')->where('groupable_members.group_type', get_class($this));
    }

    /**
     * Leave a group
     * API: $group->leave($user).
     *
     * @return int
     */
    public function leave($user)
    {
        DB::table('groupable_members')->where([
            ['group_id', '=', $this->id],
            ['group_type', '=', get_class($this)],
            ['user_id', '=', $user->id],
        ])->delete();

        // Check if user have some roles

        return DB::table('groupable_roles')->where([
            ['group_id', '=', $this->id],
            ['group_type', '=', get_class($this)],
            ['user_id', '=', $user->id],
        ])->delete();
    }

    /**
     * Return all group members.
     * API: $group->members().
     *
     * @param bool   $withRole
     * @param string $returnType (query, paginate or collection)
     * @param array  $params
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Support\Collection
     *
     * @throws \ReflectionException
     */
    public function members(array $roles = [], $withRole = false, $returnType = null, $params = [
        'perPage' => null,
        'columns' => ['*'],
        'pageName' => 'page',
        'page' => null,
    ])
    {
        $params = Arr::mergeWithDefaultParams($params);

        $members = DB::table('groupable_members')
            ->select('user_id')
            ->where('group_id', '=', $this->id)
            ->where('group_type', '=', get_class($this));

        if (count($roles)) {
            $members = $members->whereIn('role', $roles);
        }

        $members = $members->pluck('user_id');

        if ($returnType === 'query') {
            return User::query()->whereIn('id', $members);
        }

        if ($returnType || $returnType === 'paginate') {
            return User::query()
                       ->whereIn('id', $members)
                       ->paginate(
                           $params['perPage'], 
                           $params['columns'], 
                           $params['page'], 
                           $params['page']
                        );
        }

        return User::query()->whereIn('id', $members)->get();
    }

    public function scopeGroupMembers($query, $roles = [])
    {
        $members = DB::table('groupable_roles')
            ->select('user_id')
            ->where('group_id', '=', $this->id)
            ->where('group_type', '=', get_class($this))
            ->whereIn('role', $roles)
            ->pluck('user_id');

        return User::query()->whereIn('id', $members);
    }

    public function getRoleFromMember($member, $withPermissions = false)
    {
        $res = [];
        $roles = DB::table('groupable_roles')
            ->where('user_id', '=', $member->id)
            ->where('group_id', '=', $this->id)
            ->where('group_type', '=', get_class($this))
            ->get();

        // dd($roles);

        foreach ($roles as $role) {
            if ($withPermissions) {
                $permissions = DB::table('groupable_permissions')->where([
                    ['group_id', '=', $role->group_id],
                    ['group_type', '=', $role->group_type],
                    ['permission', '=', $role->role],
                ])->get();

                $role->permissions = $permissions;
            }
            $res[] = $role;
        }

        

        return $res;
    }

    /**
     * Return all group members with a given role.
     * API: $group->members().
     *
     * @param string
     * @param bool $asQuery
     *
     * @return Collection|\Illuminate\Database\Eloquent\Builder
     */
    public function membersByRole(string $role, $asQuery = false)
    {
        $members = DB::table('groupable_roles')
            ->select('user_id')
            ->where('group_id', '=', $this->id)
            ->where('group_type', '=', get_class($this))
            ->where('role', '=', $role)
            ->pluck('user_id');

        $query = User::query()->whereIn('id', $members);

        if ($asQuery) {
            return $query;
        }

        return $query->get();
    }
}
