<?php

namespace App\Repositories\Setters;

use Illuminate\Support\Str;

class ApiKeySetter
{
    public static function setApiKey($model): bool
    {
        do {
            $apiKey = Str::random(64);
        } while ($model->where('api_key', $apiKey)->exists());

        $model->api_key = $apiKey;
        return $model->save();
    }
}
