<?php

use App\Http\Controllers\Duties\CitizenFormFillUpController;
use App\Http\Controllers\Duties\DocumentVerificationController;
use App\Http\Controllers\Duties\FamilyDetailController;
use App\Http\Controllers\Duties\ProformaController;
use App\Http\Controllers\Duties\UoFileApprovalController;
use App\Http\Controllers\Duties\UoFileSubmissionController;
use App\Http\Controllers\Duties\UoFormFillUpController;
use App\Http\Controllers\Duties\UploadedDocumentController;
use App\Http\Controllers\Duties\VerifyAndForwardController;
use App\Http\Controllers\TaskApplicationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tasks', 'as' => 'tasks.performa.'], function () {
    // Optional route to view applications for a specific task
    Route::get('/all/performa', [TaskApplicationController::class, 'allProcess'])->name('all');
    Route::get('/{tasks_id}/performa', [TaskApplicationController::class, 'index'])->name('index');
    Route::post('/{tasks_id}/performa/ajaxlist', [TaskApplicationController::class, 'ajaxlist'])->name('ajaxlist');
    Route::post('/{proforma_id}/{tasks_id}/revert', [TaskApplicationController::class, 'revert'])->name('revert');
    Route::post('/{proforma_id}/{tasks_id}/reject', [TaskApplicationController::class, 'reject'])->name('reject');
});
/*
* Duties ROUTES
*/
Route::group(['prefix' => 'duties', 'as' => 'duties.'], function () {
    // proforma
    Route::group(['prefix' => 'proforma'], function () {
        Route::controller(ProformaController::class)->group(function () {
            Route::get('/', 'create')->name('proforma.create');
            Route::post('/step1', 'store')->name('proforma.store');
            Route::get('{id}/edit', 'edit')->name('proforma.edit');
            Route::get('{id}/view', 'view')->name('proforma.view');
            Route::post('{id}/update', 'update')->name('proforma.update');
            Route::delete('{id}', 'destroy')->name('proforma.destroy');
        });
    });
    Route::group(['prefix' => 'upload/document'], function () {
        Route::controller(UploadedDocumentController::class)->group(function () {
            Route::post('/', 'store')->name('upload.document.store');
            Route::get('/{id}/view', 'loadFile')->name('upload.document.load');
            Route::delete('{id}', 'destroy')->name('upload.document.destroy');
        });
    });
    Route::group(['prefix' => 'family'], function () {
        Route::controller(FamilyDetailController::class)->group(function () {
            Route::post('/{id}/add', 'store')->name('family.store');
            Route::delete('{id}', 'destroy')->name('family.destroy');
            Route::post('{id}/update', 'update')->name('family.update');
        });
    });

    Route::group(['prefix' => 'form/fillup'], function () {
        Route::controller(CitizenFormFillUpController::class)->group(function () {
            Route::get('/{tasks_id}/index', 'index')->name('form.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('form.ajaxlist');
            Route::post('/{id}/family', 'completeFamilyDetail')->name('form.completeFamilyDetail');
            Route::post('/{id}/upload', 'completeUploadDocument')->name('form.completeUploadDocument');
            Route::post('/{id}/forward', 'forward')->name('form.forward');
        });
    });

    Route::group(['prefix' => 'verify/form'], function () {
        Route::controller(VerifyAndForwardController::class)->group(function () {
            Route::get('/{tasks_id}/index', 'index')->name('verify.form.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('verify.form.ajaxlist');
            Route::get('/{id}/view', 'viewVerifyAndForward')->name('verify.form.view');

            Route::post('/{id}/verify', 'verify')->name('verify.form.verify');
            Route::post('/{id}/forward', 'forward')->name('verify.form.forward');
        });
    });
    Route::group(['prefix' => 'verify/document'], function () {
        Route::controller(DocumentVerificationController::class)->group(function () {
            //compulsory for a tasks
            Route::get('/{tasks_id}/index', 'index')->name('verify.document.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('verify.document.ajaxlist');
            Route::get('/{id}/view', 'view')->name('verify.document.view');
            Route::post('/{id}/forward', 'forward')->name('verify.document.forward');
            Route::post('/{id}/revert', 'revert')->name('verify.document.revert');
            //tasks save
            Route::post('/{id}/verify', 'verify')->name('verify.document.verify');
        });
    });
    Route::group(['prefix' => 'uo/formfillup'], function () {
        Route::controller(UoFormFillUpController::class)->group(function () {
            //compulsory
            Route::get('/{tasks_id}/index', 'index')->name('uo.formfillup.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('uo.formfillup.ajaxlist');
            Route::get('/{id}/view', 'view')->name('uo.formfillup.view');
            Route::post('/{id}/forward', 'forward')->name('uo.formfillup.forward');
        });
    });
    Route::group(['prefix' => 'uo/file/submission'], function () {
        Route::controller(UoFileSubmissionController::class)->group(function () {
            //compulsory
            Route::get('/{tasks_id}/index', 'index')->name('uo.filesubmission.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('uo.filesubmission.ajaxlist');
            Route::get('/{id}/view', 'view')->name('uo.filesubmission.view');
            Route::post('/{id}/forward', 'forward')->name('uo.filesubmission.forward');;

            Route::post('file/{id}/submit', 'submit')->name('uo.filesubmission.submit');
        });
    });

    Route::group(['prefix' => 'uo/file/approval'], function () {
        Route::controller(UoFileApprovalController::class)->group(function () {
            //compulsory
            Route::get('/{tasks_id}/index', 'index')->name('uo.file-approval.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('uo.file-approval.ajaxlist');
            Route::get('/{id}/view', 'view')->name('uo.file-approval.view');
            Route::post('/{id}/forward', 'forward')->name('uo.file-approval.forward');;

            Route::post('file/{id}/submit', 'submit')->name('uo.file-approval.submit');
        });
    });
});
