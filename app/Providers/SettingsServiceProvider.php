<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\Company; // Your Company model
use Illuminate\Support\Facades\Schema; // To check if the table exists
use Illuminate\Support\Facades\Log; // For logging errors

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Your existing boot code from AppServiceProvider (if relevant to move here)
        // For example, the Currency, StatusOptions, SourceOptions loading:
        try {
            if (Schema::hasTable('currencies')) { // Check if currencies table exists
                $currencies = \App\Models\Currency::orderBy('code')->pluck('name', 'code')->toArray();
                Config::set('globals.currencies', $currencies);
            } else {
                Config::set('globals.currencies', []); // Set empty if table not found
            }
        } catch (\Exception $e) {
            Log::error("Error loading currencies into config: " . $e->getMessage());
            Config::set('globals.currencies', []);
        }

        // Assuming statusOptions and sourceOptions are still coming from config/globals.php
        // If they were to come from the database, you'd query them here.
        // For now, let's assume config/globals.php is still the source for these.
        // If you want to merge with existing config:
        $statusOptions = config('globals.statusOptions', []);
        $sourceOptions = config('globals.sourceOptions', []);
        Config::set('globals.statusOptions', $statusOptions);
        Config::set('globals.sourceOptions', $sourceOptions);


        // Load Company Settings
        try {
            // Check if the company_settings table exists to prevent errors during migrations
            if (Schema::hasTable('company_settings')) {
                $companySettings = Company::first(); // Get the first (and only) record

                if ($companySettings) {
                    // Convert model to array and set it in config
                    // You can choose a specific key, e.g., 'company' or 'settings.company'
                    Config::set('company', $companySettings->toArray());
                } else {
                    // Handle case where settings are not yet in the DB
                    // You might set default values or log a warning
                    Log::warning('Company settings not found in database. Using default empty array.');
                    Config::set('company', []);
                }
            } else {
                // Table doesn't exist yet (e.g., during initial migration)
                Log::info('Company settings table not found, skipping config load.');
                Config::set('company', []); // Set a default empty array
            }
        } catch (\Exception $e) {
            Log::error("Error loading company settings into config: " . $e->getMessage());
            // Set default empty array or handle error as appropriate
            Config::set('company', []);
        }

        // Other boot logic from your AppServiceProvider (like KTBootstrap, Livewire routes)
        // can remain in AppServiceProvider or be moved here if purely settings/bootstrap related.
        // For simplicity, let's keep them separate unless they are tightly coupled.
        // Builder::defaultStringLength(191); // Usually remains in AppServiceProvider
        // KTBootstrap::init(); // If this is global bootstrap, maybe AppServiceProvider is better

        // if (app()->environment('production')) {
        //     Livewire::setUpdateRoute(function ($handle) {
        //         return Route::post('/starterkit/metronic/laravel/livewire/update', $handle);
        //     });
        // }
    }
}
