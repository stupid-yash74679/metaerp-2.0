<?php

use App\Http\Controllers\Apps\Contacts\ContactGroupManagementController;
use App\Http\Controllers\Apps\Contacts\ContactManagementController;
use Illuminate\Support\Facades\Route;

Route::name('contacts.')->group(function () {
    Route::get('/contacts/add', [ContactManagementController::class, 'add'])->name('add');
    Route::resource('/contacts/contact-groups', ContactGroupManagementController::class);
    Route::resource('/contacts', ContactManagementController::class)->only(['index', 'show'])->names([
        'index' => 'index', // This will generate 'contacts.index'
        'show' => 'contacts.show', // This will generate 'contacts.contacts.show' (due to group + explicit)
    ]);
    Route::post('/contacts/{contact?}', [ContactManagementController::class, 'storeOrUpdate'])->name('storeOrUpdate');
});
