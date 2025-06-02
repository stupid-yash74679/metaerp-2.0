<?php

use App\Http\Controllers\Apps\CRM\LeadManagementController;
use App\Http\Controllers\Apps\CRM\ProposalManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('leads')->name('leads.')->group(function () {
    Route::get('/add', [LeadManagementController::class, 'add'])->name('add'); // leads.add
    Route::get('/', [LeadManagementController::class, 'index'])->name('index'); // leads.index
    Route::get('/{lead}', [LeadManagementController::class, 'show'])->name('show'); // leads.show
    Route::post('/{lead?}', [LeadManagementController::class, 'storeOrUpdate'])->name('storeOrUpdate'); // leads.storeOrUpdate
    Route::delete('/{lead}', [LeadManagementController::class, 'destroy'])->name('destroy'); // leads.destroy
});
