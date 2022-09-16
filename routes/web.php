<?php

use App\Exports\ExportMap;
use Maatwebsite\Excel\Facades\Excel;
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

    Route::controller(App\Http\Controllers\School\StudentDiaryController::class)->group(function () {
        Route::get('aktivitas/studentdiaries/plp{plp}','index')->name('studentdiaries.index');
        Route::get('aktivitas/studentdiaries/plp{plp}/create','create')->name('studentdiaries.create');
        Route::post('aktivitas/studentdiaries/plp{plp}','store')->name('studentdiaries.store');
        Route::get('aktivitas/studentdiaries/plp{plp}/{studentdiary}/edit','edit')->name('studentdiaries.edit');
        Route::put('aktivitas/studentdiaries/plp{plp}/{studentdiary}','update')->name('studentdiaries.update');
        Route::delete('aktivitas/studentdiaries/plp{plp}/{studentdiary}','destroy')->name('studentdiaries.destroy');
    });

    Route::controller(App\Http\Controllers\School\DiaryVerificationController::class)->group(function () {
        Route::get('aktivitas/diaryverifications/plp{plp_order}','index')->name('diaryverifications.index');
        Route::get('aktivitas/diaryverifications/plp{plp_order}/{map_id}','show')->name('diaryverifications.show');
        Route::put('aktivitas/diaryverifications/plp{plp_order}/{map_id}/{diaryverification}','update')->name('diaryverifications.update');
    });
    Route::controller(App\Http\Controllers\School\StudentObservationController::class)->group(function () {
        Route::get('aktivitas/studentobservations','index')->name('studentobservations.index');
        Route::get('aktivitas/studentobservations/{form_id}/create','create')->name('studentobservations.create');
        Route::post('aktivitas/studentobservations/{form_id}','store')->name('studentobservations.store');
        Route::get('aktivitas/studentobservations/{form_id}/{studentobservation}/edit','edit')->name('studentobservations.edit');
        Route::put('aktivitas/studentobservations/{form_id}/{studentobservation}','update')->name('studentobservations.update');
    });
    Route::controller(App\Http\Controllers\School\AssessmentController::class)->group(function () {
        Route::get('aktivitas/schoolassessments/plp{plp_order}','index')->name('schoolassessments.index');
        Route::get('aktivitas/schoolassessments/plp{plp_order}/{form_id}/','show')->name('schoolassessments.show');
        Route::get('aktivitas/schoolassessments/plp{plp_order}/{form_id}/{form_order}/{map_id}/create','create')->name('schoolassessments.create');
        Route::post('aktivitas/schoolassessments/plp{plp_order}/{form_id}/{form_order}/{map_id}','store')->name('schoolassessments.store');
        Route::get('aktivitas/schoolassessments/plp{plp_order}/{form_id}/{form_order}/{map_id}/{schoolassessment}/edit','edit')->name('schoolassessments.edit');
        Route::put('aktivitas/schoolassessments/plp{plp_order}/{form_id}/{form_order}/{map_id}/{schoolassessment}','update')->name('schoolassessments.update');
    });
    Route::get('aktivitas/reportprint/plp{plp_order}', [App\Http\Controllers\School\ReportPrintController::class, 'generateCover'])->name('generateCover');
    Route::get('aktivitas/teachingrespons',function(){
        return view('aktivitas.teachingrespon-list');
    });
    Route::get('report/mappingresult',function(){
        return view('report.mapping-result');
    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/export-maps',function(){
    $file_name = 'mapping'.date('YmdHis').'.xlsx';
    return Excel::download(new ExportMap, $file_name);
});

