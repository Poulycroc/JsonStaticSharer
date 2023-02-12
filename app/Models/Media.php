<?php

namespace App\Models;

use App\Traits\IsGroupable;
// use App\Traits\GroupContent;
// use App\Traits\IsGroup;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    // use GroupContent;
    // use IsGroup;
    use IsGroupable;

    protected $table = 'media';

    public function findByUuid(string $uuid)
    {
        return $this->where('uuid', $uuid)->first();
    }
}
