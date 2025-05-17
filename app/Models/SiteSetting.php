<?php

namespace App\Models;

use App\Services\SiteSettingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $guarded = [''];


    protected static function booted()
    {
        // static::saved(function () {
        //     app(SiteSettingService::class)->refreshCache();
        // });

        // static::deleted(function () {
        //     app(SiteSettingService::class)->refreshCache();
        // });
    }
}
