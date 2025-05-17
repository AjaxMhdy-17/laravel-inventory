<?php

namespace App\Observers;

use App\Models\SiteSetting;
use App\Services\SiteSettingService;

class SiteSettingObserver
{
    /**
     * Handle the SiteSetting "created" event.
     */
    public function created(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "updated" event.
     */
    public function updated(SiteSetting $siteSetting): void
    {
        app(SiteSettingService::class)->refreshCache();
    }

    /**
     * Handle the SiteSetting "deleted" event.
     */
    public function deleted(SiteSetting $siteSetting): void
    {
        app(SiteSettingService::class)->refreshCache();
    }

    /**
     * Handle the SiteSetting "restored" event.
     */
    public function restored(SiteSetting $siteSetting): void
    {
        //
    }

    /**
     * Handle the SiteSetting "force deleted" event.
     */
    public function forceDeleted(SiteSetting $siteSetting): void
    {
        //
    }
}
