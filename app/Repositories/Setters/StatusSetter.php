<?php

namespace App\Repositories\Setters;

use App\Models\User;
use App\Traits\ContentPublishing\Status as PublishStatus;
use Carbon\Carbon;

/**
 * Common trait to define an OwnerSetter.
 */
trait StatusSetter
{
    public static function setSubmitStatus($model)
    {
        return self::setStatus($model, 'SUBMITTED');
    }

    public static function setApproveStatus($model)
    {
        return self::setStatus($model, 'APPROVED');
    }

    public static function setRejectedStatus($model)
    {
        return self::setStatus($model, 'REJECTED');
    }

    public static function setPublishedStatus($model)
    {
        return self::setStatus($model, 'PUBLISHED');
    }

    public static function setArchivedStatus($model)
    {
        return self::setStatus($model, 'ARCHIVED');
    }

    public static function setDraftedStatus($model)
    {
        return self::setStatus($model, 'DRAFTED');
    }

    /**
     * Set current user as admin/owner of the entities.
     *
     * @param $model JoinsGroups|GroupMembers|GroupRoles
     */
    public static function setStatus($model, string $statusName): bool
    {
        $publishStatus = new PublishStatus();

        $model->status = $publishStatus->getStatusFromName($statusName);

        if ($statusName === 'PUBLISHED') {
            $model->published_by = auth()->user()->id;
            $model->published_at = Carbon::now();
        }

        return $model->save();
    }
}
