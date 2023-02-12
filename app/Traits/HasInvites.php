<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasInvites
{
    /**
     * @return mixed
     */
    public function invites(): MorphOne
    {
        return $this->morphOne(Invite::class, 'claimer');
    }
}
