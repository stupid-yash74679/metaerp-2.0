<?php
use App\Http\Controllers\Apps\System\CurrencyManagementController;
use App\Http\Controllers\Apps\System\CustomFieldManagementController;
use App\Http\Controllers\Apps\System\TaxRateManagementController;
use Illuminate\Support\Facades\Route;

Route::name('system.')->group(function () {
        Route::resource('/system/currencies', CurrencyManagementController::class);
        Route::resource('/system/custom-fields', CustomFieldManagementController::class); // Added Custom Field routes
        Route::resource('/system/message-templates', \App\Http\Controllers\System\MessageTemplateManagementController::class);
        Route::resource('/system/tax-rates', TaxRateManagementController::class)->names('tax-rates');
        Route::get('/system/company-settings', [\App\Http\Controllers\System\CompanySettingsController::class, 'edit'])->name('company-settings.edit');
        Route::put('/system/company-settings', [\App\Http\Controllers\System\CompanySettingsController::class, 'update'])->name('company-settings.update');
    });
