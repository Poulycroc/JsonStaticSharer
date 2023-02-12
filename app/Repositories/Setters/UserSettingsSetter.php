<?php

namespace App\Repositories\Setters;

use App\Models\UserSettings;

/**
 * Common trait to define an UserSettingsSetter.
 */
trait UserSettingsSetter
{
    public static function setSettings($user, string $type, $settings): bool
    {
        $options = json_decode($settings);

        $userSettings = new UserSettings();
        $userSettings->type = $type;
        $userSettings->settings = $options;
        $userSettings->user_id = $user->id;

        return $userSettings->save();
    }
}
