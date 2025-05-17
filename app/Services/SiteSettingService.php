<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingService
{
    const CACHE_KEY = "site_settings";

    protected static $setting = null;

    public function getSettings()
    {
        if (self::$setting !== null) {
            return self::$setting;
        }
        return self::$setting = Cache::rememberForever(self::CACHE_KEY, function () {
            return SiteSetting::first();
        });
    }

    public function refreshCache()
    {
        Cache::forget(self::CACHE_KEY);
        self::$setting = null;
        $this->getSettings();
    }
}
