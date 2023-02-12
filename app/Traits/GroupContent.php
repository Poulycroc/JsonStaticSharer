<?php

namespace App\Traits;

use App\Models\Groupable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

trait GroupContent
{
    /**
     * Return all groupable models.
     * API: $group->types().
     *
     * @return array
     */
    public function types()
    {
        return $this->groupable_models;
    }

    /**
     * Add content to group.
     * API: $group->addContent($content).
     *
     * @return bool
     *
     * @throws Exception
     */
    public function addContent($content)
    {
        if (in_array(get_class($content), $this->groupable_models, false)) {
            return DB::table('groupables')->insert([
                'group_id' => $this->id,
                'group_type' => get_class($this),
                'groupable_id' => $content->id,
                'groupable_type' => get_class($content),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }

        throw new Exception('Content of type '.get_class($content).' can not be added to group of type '.get_class($this).'.');
    }

    /**
     * Remove content from group.
     * API: $group->removeContent($content).
     *
     * @return int
     */
    public function removeContent($content)
    {
        return DB::table('groupables')->where([
            ['group_id', '=', $this->id],
            ['group_type', '=', get_class($this)],
            ['groupable_id', '=', $content->id],
            ['groupable_type', '=', get_class($content)],
        ])->delete();
    }

    /**
     * Return all group content - optionally restrict by type.
     * API: $group->content().
     *
     * @param array
     *
     * @return \Illuminate\Support\Collection
     */
    public function content(array $types = [])
    {
        $contents = DB::table('groupables')
            ->select('groupable_id', 'groupable_type')
            ->where([
                ['group_id', '=', $this->id],
                ['group_type', '=', get_class($this)],
            ])
            ->where(Groupable::whereClause('groupable_type', $types))
            ->get();

        $collection = collect([]);

        foreach ($contents as $content) {
            $collection->push(Groupable::resolveModel($content->groupable_type, $content->groupable_id));
        }

        return $collection;
    }

    /**
     * Return all group content - optionally restrict by type.
     * API: $group->content().
     *
     * @param array
     *
     * @return \Illuminate\Support\Collection
     */
    public function parentGroup(array $types = [])
    {
        $contents = DB::table('groupables')
            ->select('group_id', 'group_type')
            ->where([
                ['groupable_id', '=', $this->id],
                ['groupable_type', '=', get_class($this)],
            ])
            ->where(Groupable::whereClause('group_type', $types))
            ->get();

        $collection = collect([]);

        foreach ($contents as $content) {
            $res = Groupable::resolveModel($content->group_type, $content->group_id);
            if ($res !== null) {
                $collection->push($res);
            }
        }

        return $collection;
    }
}
