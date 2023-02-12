<?php

namespace App\Repositories\Setters;

/**
 * Common trait to define an OwnerSetter.
 */
trait LangSetter
{
    public static function setLang($model, string $lang = null): bool
    {
        $model->lang = $lang === null ? 'fr' : $lang;

        return $model->save();
    }
}
