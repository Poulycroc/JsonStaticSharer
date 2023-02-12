<?php

namespace App\Repositories\Setters;

/**
 * Common trait to define an OwnerSetter.
 */
trait PublicationDateSetter
{
    public static function setPublicationDates($model, array $dates)
    {
        $start = !empty($dates['start']) ? $dates['start'] : null;
        $end = !empty($dates['end']) ? $dates['end'] : null;

        $start = $start !== null ? date('Y-m-d H:i:s', strtotime($start)) : null;
        $end = $end !== null ? date('Y-m-d H:i:s', strtotime($end)) : null;

        $model->publication_start = $start;
        $model->publication_end = $end;
        $model->save();
    }

    public static function setPropositionPublicationDates($model, array $dates)
    {
        $start = !empty($dates['start']) ? $dates['start'] : null;
        $end = !empty($dates['end']) ? $dates['end'] : null;

        $start = $start !== null ? date('Y-m-d H:i:s', strtotime($start)) : null;
        $end = $end !== null ? date('Y-m-d H:i:s', strtotime($end)) : null;

        $model->proposition_publication_start = $start;
        $model->proposition_publication_end = $end;
        $model->save();
    }
}
