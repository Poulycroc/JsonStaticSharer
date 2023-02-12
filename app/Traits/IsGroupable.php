<?php

namespace App\Traits;

use App\Models\Groupable;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| IsGroupable
|--------------------------------------------------------------------------
|
| Use this trait in one of your models in order to make it groupable.
|
*/

trait IsGroupable
{
    /**
     * Get Groups it Belongs To
     * API: $model->groups().
     *
     * @return array
     */
    public function groups()
    {
        $collection = collect([]);

        $groups = DB::table('groupables')
                    ->select('group_id', 'group_type')
                    ->where('groupable_id', '=', $this->id)
                    ->where('groupable_type', '=', get_class($this))
                    ->get();

        foreach ($groups as $group) {
            $collection->push(Groupable::resolveModel($group->group_type, $group->group_id));
        }

        return $collection;
    }

    public function groupables()
    {
        return $this->morphMany(Groupable::class, 'groupable');
    }

    public function scopeInGroup($query, $model)
    {
        return $this->whereHas('groupables', function ($query) use ($model) {
            return $query->where('group_id', $model->id)->where('group_type', get_class($model));
        });
    }
}
