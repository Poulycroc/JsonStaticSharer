<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\IsGroupable;
use App\Traits\HasUuid;

class Options extends Model
{
    use IsGroupable;
    use HasUuid;

    protected $table = 'options';

    /**
     * Define all the models that could be linked to a group.
     *
     * @var array
     */
    protected $groupable_models = [
        EventGuests::class,
    ];
}
