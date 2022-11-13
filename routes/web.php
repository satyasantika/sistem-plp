<?php

use App\Exports\ExportMap;
use App\Exports\ExportProfile;
use App\Exports\ExportYudisium;
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
    Route::post('/password/reset/{id}', [App\Http\Controllers\Auth\PasswordChangeController::class, 'resetPasswordPost'])->name('reset-password');
    Route::resource('profiles', App\Http\Controllers\ProfileController::class)->only(['index','edit','update']);
    Route::resource('konfigurasi/roles', App\Http\Controllers\Configuration\RoleController::class)->except('show');
    Route::resource('konfigurasi/permissions', App\Http\Controllers\Configuration\PermissionController::class)->except('show');
    Route::resource('konfigurasi/rolepermissions', App\Http\Controllers\Configuration\RolePermissionController::class)->only('edit', 'update');
    Route::resource('konfigurasi/userpermissions', App\Http\Controllers\Configuration\UserPermissionController::class)->only('edit', 'update');
    Route::resource('konfigurasi/userroles', App\Http\Controllers\Configuration\UserRoleController::class)->only('edit', 'update');
    Route::resource('konfigurasi/users', App\Http\Controllers\Configuration\UserController::class)->except('show');
    Route::resource('konfigurasi/navigations', App\Http\Controllers\Configuration\NavigationController::class)->except('show');
    Route::resource('konfigurasi/schools', App\Http\Controllers\Configuration\SchoolController::class)->except('show');
    Route::resource('konfigurasi/schooluserproposals', App\Http\Controllers\Configuration\SchoolUserProposalController::class)->except('show');
    Route::resource('konfigurasi/maps', App\Http\Controllers\Configuration\MapController::class)->except('show');
    Route::resource('konfigurasi/diaries', App\Http\Controllers\Configuration\DiaryController::class)->except('show');
    Route::resource('konfigurasi/forms', App\Http\Controllers\Configuration\FormController::class)->except('show');
    Route::resource('konfigurasi/formitems', App\Http\Controllers\Configuration\FormItemController::class)->except('show');
    Route::resource('konfigurasi/assessments', App\Http\Controllers\Configuration\AssessmentController::class)->except('show');
    Route::resource('konfigurasi/observations', App\Http\Controllers\Configuration\ObservationController::class)->except('show');
    Route::resource('data/cleaningassessments', App\Http\Controllers\School\CleaningAssessmentController::class)->only(['index','destroy']);
    Route::resource('usulan/schoolcoordinators', App\Http\Controllers\School\CoordinatorProposalController::class)->except('show');
    Route::resource('usulan/schoolteachers', App\Http\Controllers\School\TeacherProposalController::class)->except('show');
    Route::resource('mapping/mastermaps', App\Http\Controllers\Map\MasterMapController::class)->except('show');
    Route::resource('mapping/departementmaps', App\Http\Controllers\Map\DepartementMapController::class)->only(['index','edit','update']);
    Route::resource('mapping/teachermaps', App\Http\Controllers\Map\TeacherMapController::class)->only(['index','edit','update']);

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
    })->middleware('permission:aktivitas/teachingrespons-read');
    Route::get('report/mappingresult',function(){
        return view('report.mapping-result');
    })->middleware('permission:report/mappingresult-read');
    Route::get('report/mappingquota',function(){
        return view('report.mapping-quota');
    })->middleware('permission:report/mappingquota-read');
    Route::get('report/summary',function(){
        return view('report.summary');
    })->middleware('permission:report/summary-read');
    Route::get('report/schooluserproposal',function(){
        return view('report.user-propose');
    })->middleware('permission:report/schooluserproposal-read');
    Route::get('data/progress/profile',function(){
        return view('report.profile');
    })->middleware('permission:data/progress/profile-read');
    Route::get('yudisium/plp{plp_order}',function($plp_order){
        return view('report.yudisium',compact('plp_order'));
    })->middleware('permission:yudisium/plp1-read|yudisium/plp2-read');
    Route::get('data/progress/plp{plp_order}',function($plp_order){
        return view('report.assessment-result',compact('plp_order'));
    })->middleware('permission:data/progress/plp1-read|data/progress/plp2-read|'.request()->segment(3).'-read');
    //TMP
    Route::get('data/schooluserapprovals',[App\Http\Controllers\School\SchoolUserController::class, 'index']);

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/export-maps',function(){
    $file_name = 'mapping'.date('YmdHis').'.xlsx';
    return Excel::download(new ExportMap, $file_name);
});
Route::get('/export-yudisium/{plp_order}',function($plp_order){
    $file_name = 'yudisium-'.$plp_order.'-'.date('YmdHis').'.xlsx';
    return Excel::download(new ExportYudisium, $file_name);
});
Route::get('/export-profil-gp',function(){
    $file_name = 'profile-gp-'.date('YmdHis').'.xlsx';
    return Excel::download(new ExportProfile, $file_name);
});

