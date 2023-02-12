<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\IsGroup;
use App\Traits\Archivable;
use App\Traits\HasUuid;

class Project extends Model
{
    use HasFactory;
    use IsGroup;
    use Archivable;
    use HasUuid;

    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $groupable_models = [
        // MetaDataBlock::class,
    ];
}
