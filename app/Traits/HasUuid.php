<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * @author    Yannick Delwiche <yannick.delwiche@freecaster.com>
 * @copyright Freecaster 2019
 */
trait HasUuid
{
    protected function createUuidField($table, string $after = 'id', string $column = 'uuid')
    {
        DB::statement('ALTER TABLE `'.$table.'` ADD `'.$column.'` char(36) AFTER `'.$after.'`');
        DB::statement('CREATE UNIQUE INDEX unique_uuid ON `'.$table.'` (`'.$column.'`)');
    }

    public function findByUuid(string $uuid)
    {
        return $this->where('uuid', $uuid)->first();
    }
}
