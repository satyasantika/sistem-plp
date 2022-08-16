<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

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

require __DIR__.'/auth.php';

Route::get('/change-password', [App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangePasswordGet'])->name('change-password');
Route::post('/change-password', [App\Http\Controllers\Auth\PasswordChangeController::class, 'changePasswordPost'])->name('update-password');

Route::resource('konfigurasi/roles', RoleController::class)->middleware('role:admin');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
