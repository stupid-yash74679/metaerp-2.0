<?php

use App\Http\Controllers\Apps\Contacts\ContactGroupManagementController;
use App\Http\Controllers\Apps\Contacts\ContactManagementController;
use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Apps\System\CurrencyManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Apps\CRM\LeadManagementController;
use App\Http\Controllers\Apps\Projects\ProjectTypeManagementController;
use App\Http\Controllers\Apps\System\CustomFieldManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });



    require __DIR__ . '/app/system.php';
    require __DIR__ . '/app/contacts.php';
    require __DIR__ . '/app/crm.php';
    // Projects > Project Types
    Route::prefix('projects')->name('projects.')->group(function () {
        // Redirect /projects to project-types index
        Route::get('/', function () {
            return redirect()->route('projects.project-types.index');
        })->name('index'); // projects.index

        Route::prefix('project-types')->name('project-types.')->group(function () {
            Route::get('/add', [ProjectTypeManagementController::class, 'add'])->name('add'); // projects.project-types.add
            Route::get('/', [ProjectTypeManagementController::class, 'index'])->name('index'); // projects.project-types.index
            Route::get('/{projectType}', [ProjectTypeManagementController::class, 'show'])->name('show'); // projects.project-types.show
            Route::post('/{projectType?}', [ProjectTypeManagementController::class, 'storeOrUpdate'])->name('storeOrUpdate'); // projects.project-types.storeOrUpdate
            Route::delete('/{projectType}', [ProjectTypeManagementController::class, 'destroy'])->name('destroy'); // projects.project-types.destroy
        });
    });
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';
