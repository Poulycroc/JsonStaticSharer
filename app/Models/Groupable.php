<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupable extends Model
{
    protected static $cachedModel = [];

    protected $table = "groupables";

    protected $fillable = [
        'group_id',
        'group_type',
        'groupable_id',
        'groupable_type'
    ];

    /**
     * Return a model from a type and id.
     * API: $resolveModel($model_type, $model_id)
     *
     * @param $model_type
     * @param $model_id
     * @return  array
     */
    public static function resolveModel($model_type, $model_id)
    {
        $key = $model_type.$model_id;

        if(isset(self::$cachedModel[$key])) return self::$cachedModel[$key];

        return self::$cachedModel[$key] = $model_type::find($model_id);
    }

    public function group(){
        return $this->morphTo();
    }

    public function groupable(){
        return $this->morphTo();
    }

    /**
     * Return an array for a where clause.
     * API: $whereClause($array)
     *
     * @return  array
     */
    public static function whereClause($column, $array)
    {
        $queryArray = [];

        foreach ($array as $value) {
            $queryArray[] = [$column, '=', $value];
        }

        return $queryArray;
    }
}
