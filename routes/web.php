<?php


use App\Http\Controllers\Authentication\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Duties\VerificationController;
use App\Http\Controllers\Misc\DistrictController;
use App\Http\Controllers\Misc\SubDivisionController;
use App\Http\Controllers\TaskApplicationController;

// Route::get('/phpinfo', function () {
//     phpinfo();
// });
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    echo 'Application cache has been cleared';
});


Route::get('/', [LoginController::class, 'login'])->name('home');

Route::group(['prefix' => 'duty', 'as' => 'duty.personal.'], function () {
    Route::get('/viewPersonalDetailsFrom/{id}', [VerificationController::class, 'viewDetail'])->name('detail');
});

Route::group(['prefix' => 'tasks', 'as' => 'tasks.performa.'], function () {
    // Optional route to view applications for a specific task
    Route::get('/all/performa', [TaskApplicationController::class, 'allProcess'])->name('all');
    Route::get('/{task_id}/performa', [TaskApplicationController::class, 'index'])->name('index');
    Route::post('/{task_id}/performa/ajaxlist', [TaskApplicationController::class, 'ajaxlist'])->name('ajaxlist');
});



//misc routes
Route::group(['prefix' => 'misc', 'as' => 'misc.option.'], function () {
    Route::get('/{id}/district', [DistrictController::class, 'loadByState'])->name('district');
    Route::get('/{id}/subdivision', [SubDivisionController::class, 'loadByDistrict'])->name('subdivision');
});
