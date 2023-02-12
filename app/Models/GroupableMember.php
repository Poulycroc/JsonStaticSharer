<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupableMember extends Model
{
    protected $table = "groupable_members";

    public function group()
    {
        return $this->morphTo("group");
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
