<?php

namespace App\Repositories\Setters;

use Ramsey\Uuid\Uuid;

/**
 * Common trait to define an OwnerSetter.
 */
trait UuidSetter
{
    public static function setUuid($model): bool
    {
        $uuid = Uuid::uuid4();
        $model->uuid = $uuid->toString();

        return $model->save();
    }
}
