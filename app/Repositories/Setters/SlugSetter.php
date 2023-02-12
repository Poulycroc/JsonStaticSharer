<?php

namespace App\Repositories\Setters;

use Illuminate\Support\Str;

/**
 * Common trait to define an SlugSetter.
 */
trait SlugSetter
{
    public static function setSlug($model): bool
    {
        $options = json_decode($model->options);
        $place = '';

        if ($model->type === 'event' && isset($options)) {
            $place = $options->isOnline === true ? 'online' : $options->place;
        }

        $clientName = $model->company()->name;
        $campaignName = $model->parent->name;
        $name = $model->name;

        $model->slug = Str::slug(implode(' ', [$clientName, $campaignName, $place, $name]), '-');

        return $model->save();
    }
}
