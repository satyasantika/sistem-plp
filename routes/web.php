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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangePasswordGet'])->name('change-password');
    Route::post('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'changePasswordPost'])->name('update-password');
    Route::resource('konfigurasi/roles', App\Http\Controllers\RoleController::class)->except('show');
    Route::resource('konfigurasi/permissions', App\Http\Controllers\PermissionController::class)->except('show');
    Route::resource('konfigurasi/rolepermissions', App\Http\Controllers\RolePermissionController::class)->only('edit', 'update');
    Route::resource('konfigurasi/userroles', App\Http\Controllers\UserRoleController::class)->only('edit', 'update');
    Route::resource('konfigurasi/users', App\Http\Controllers\UserController::class)->except('show');
    Route::resource('konfigurasi/navigations', App\Http\Controllers\NavigationController::class)->except('show');
    Route::resource('konfigurasi/schools', App\Http\Controllers\SchoolController::class)->except('show');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
