<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            try {
                // Cache settings for 5 minutes to reduce database queries
                $settings = Cache::remember('app_settings', 300, function () {
                    return Setting::all()->pluck('value', 'key')->toArray();
                });

                $view->with('appSettings', $settings);

                // Set timezone dynamically
                if (isset($settings['app_timezone'])) {
                    Config::set('app.timezone', $settings['app_timezone']);
                    date_default_timezone_set($settings['app_timezone']);
                }

                // Set app name dynamically
                if (isset($settings['app_name'])) {
                    Config::set('app.name', $settings['app_name']);
                }
            } catch (\Exception $e) {
                $view->with('appSettings', []);
            }
        });
    }
}
