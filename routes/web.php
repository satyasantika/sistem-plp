<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\RoleController;

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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangePasswordGet'])->name('change-password');
    Route::post('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'changePasswordPost'])->name('update-password');
    Route::resource('profiles', App\Http\Controllers\ProfileController::class)->only(['index','edit','update']);
    Route::resource('konfigurasi/roles', App\Http\Controllers\RoleController::class)->except('show');
    Route::resource('konfigurasi/permissions', App\Http\Controllers\PermissionController::class)->except('show');
    Route::resource('konfigurasi/rolepermissions', App\Http\Controllers\RolePermissionController::class)->only('edit', 'update');
    Route::resource('konfigurasi/userpermissions', App\Http\Controllers\UserPermissionController::class)->only('edit', 'update');
    Route::resource('konfigurasi/userroles', App\Http\Controllers\UserRoleController::class)->only('edit', 'update');
    Route::resource('konfigurasi/users', App\Http\Controllers\UserController::class)->except('show');
    Route::resource('konfigurasi/navigations', App\Http\Controllers\NavigationController::class)->except('show');
    Route::resource('konfigurasi/schools', App\Http\Controllers\SchoolController::class)->except('show');
    Route::resource('konfigurasi/schooluserproposals', App\Http\Controllers\SchoolUserProposalController::class)->except('show');
    Route::resource('konfigurasi/maps', App\Http\Controllers\MapController::class)->except('show');
    Route::resource('konfigurasi/diaries', App\Http\Controllers\DiaryController::class)->except('show');
    Route::resource('konfigurasi/forms', App\Http\Controllers\FormController::class)->except('show');
    Route::resource('konfigurasi/formitems', App\Http\Controllers\FormItemController::class)->except('show');
    Route::resource('konfigurasi/assessments', App\Http\Controllers\AssessmentController::class)->except('show');
    Route::resource('konfigurasi/observations', App\Http\Controllers\ObservationController::class)->except('show');
    Route::resource('usulan/school_coordinators', App\Http\Controllers\School\CoordinatorProposalController::class)->except('show');
    Route::resource('usulan/school_teachers', App\Http\Controllers\School\TeacherProposalController::class)->except('show');
    Route::resource('mapping/mastermaps', App\Http\Controllers\Map\MasterMapController::class)->except('show');
    Route::resource('mapping/departementmaps', App\Http\Controllers\Map\DepartementMapController::class)->only(['index','edit','update']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
