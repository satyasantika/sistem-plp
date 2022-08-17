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

// require __DIR__.'/auth.php';

Route::get('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangePasswordGet'])->name('change-password');
Route::post('/password/change', [App\Http\Controllers\Auth\PasswordChangeController::class, 'changePasswordPost'])->name('update-password');
Route::get('/register/with-role', [App\Http\Controllers\Auth\Custom\RegisterController::class, 'showRegistrationForm'])->name('register-with-role');
Route::post('/register/with-role', [App\Http\Controllers\Auth\Custom\RegisterController::class, 'register']);

Route::resource('konfigurasi/roles', RoleController::class)->middleware('role:admin');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
