<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Activity::saving(function (Activity $activity) {
            $activity->ip = request()->ip();
        });

        // register addon service providers
        try {
            $registeredAddonsFile = base_path('bootstrap/cache/registeredAddons.php');

            if (!file_exists($registeredAddonsFile)) {
                return;
            }

            // get registered addons from cache file
            $addons = require $registeredAddonsFile;

            if (!is_array($addons)) {
                return;
            }

            // loop through addons and register their service providers
            foreach ($addons as $addonId => $addon) {
                if (($addon['status'] ?? 'inactive') !== 'active') {
                    continue;
                }

                $providerClass = $addon['name'] ?? null;

                if (!$providerClass) {
                    continue;
                }
                // we will try to include the provider file manually since addons aren't autoloaded via composer.
                $parts = explode('\\', $providerClass);
                $folderName = $parts[1] ?? null;
                $providerFileName = class_basename($providerClass);

                if (!$folderName || !$providerFileName) {
                    continue;
                }

                // construct provider file path and include it
                $providerFile = base_path("addons/{$folderName}/src/{$providerFileName}.php");

                // if the provider exists, include it so that the class becomes available for registration
                if (file_exists($providerFile)) {
                    require_once $providerFile;
                }

                // finally, if the class exists after including the file, we can register it with the app container
                if (class_exists($providerClass, false)) {
                    app()->register($providerClass);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Addon provider registration failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
