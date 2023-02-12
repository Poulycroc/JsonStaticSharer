<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Invite extends Model
{
    protected $table = 'invites';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $dates = ['claimed_at'];

    // public function creator()
    // {
    //     return $this->morphTo();
    // }

    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    //  */
    // public function claimer()
    // {
    //     return $this->morphTo();
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function claimer()
    {
        return $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public function creator()
    {
        return $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function object()
    {
        return $this->morphTo();
    }

    /**
     * @return Model
     */
    public function claim(Model $claimer)
    {
        $this->claimer()->associate($claimer);
        $this->claimed_at = Carbon::now();

        return (bool) $this->save();
    }

    /**
     * @return bool
     */
    public function claimed()
    {
        return !empty($this->claimed_at);
    }

    /**
     * @param $data
     *
     * @return static
     */
    public static function getNewCode($data)
    {
        $data['code'] = bin2hex(openssl_random_pseudo_bytes(16));

        return static::create($data);
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public static function getInviteByCode($code)
    {
        return static::where('code', '=', $code)->first();
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public static function getValidInviteByCode($code)
    {
        return static::where('code', '=', $code)
                    ->where('claimed_at', '=', null)
                    ->first();
    }
}
