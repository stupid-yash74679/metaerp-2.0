<?php

use App\Http\Controllers\Apps\Projects\ProjectTypeManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects')->name('projects.')->group(function () {
        Route::resource('project-types', ProjectTypeManagementController::class);
    });
