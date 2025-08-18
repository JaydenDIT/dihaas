<?php

use App\Http\Controllers\Duties\CitizenFormFillUpController;
use App\Http\Controllers\Duties\DocumentVerificationController;
use App\Http\Controllers\Duties\FamilyDetailController;
use App\Http\Controllers\Duties\ProformaController;
use App\Http\Controllers\Duties\UoFileSubmissionController;
use App\Http\Controllers\Duties\UoFormFillUpController;
use App\Http\Controllers\Duties\UploadedDocumentController;
use App\Http\Controllers\Duties\VerifyAndForwardController;
use Dom\Document;
use Illuminate\Support\Facades\Route;
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
            Route::get('/performa/{id}/view', 'view')->name('proforma.view');
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
    Route::group(['prefix' => 'citizen'], function () {
        Route::controller(CitizenFormFillUpController::class)->group(function () {
            Route::post('/{id}/step2', 'completeFamilyDetail')->name('form.completeFamilyDetail');
            Route::post('/{id}/step3', 'completeUploadDocument')->name('form.completeUploadDocument');
            Route::post('form/{id}/submit', 'proformaFormSubmit')->name('form.final.submit');
            Route::get('/{tasks_id}/performa', 'index')->name('form.index');
            Route::post('/{tasks_id}/performa/ajaxlist', 'ajaxlist')->name('form.ajaxlist');
        });
    });
    Route::group(['prefix' => 'verify/form'], function () {
        Route::controller(VerifyAndForwardController::class)->group(function () {
            Route::get('/{tasks_id}/proforma', 'index')->name('verify.form.index');
            Route::post('/{tasks_id}/proforma/ajaxlist', 'ajaxlist')->name('verify.form.ajaxlist');
            Route::get('/verify/{id}/view', 'view')->name('verify.form.view');

            Route::post('/verify/{id}/verify', 'verify')->name('verify.form.verify');
            Route::post('/verify/{id}/revert', 'revert')->name('verify.form.revert');
            Route::post('/verify/{id}/forward', 'forward')->name('verify.form.forward');
            Route::post('/verify/{id}/reject', 'reject')->name('verify.form.reject');
        });
    });
    Route::group(['prefix' => 'verify/document'], function () {
        Route::controller(DocumentVerificationController::class)->group(function () {
            //compulsory for a tasks
            Route::get('/{tasks_id}/proforma', 'index')->name('verify.document.index');
            Route::post('/{tasks_id}/proforma/ajaxlist', 'ajaxlist')->name('verify.document.ajaxlist');
            Route::get('/verify/{id}/view', 'view')->name('verify.document.view');
            Route::post('/verify/{id}/revert', 'revert')->name('verify.document.revert');
            Route::post('/verify/{id}/forward', 'forward')->name('verify.document.forward');
            Route::post('/verify/{id}/reject', 'reject')->name('verify.document.reject');
            //tasks save 
            Route::post('/verify/{id}/verify', 'verify')->name('verify.document.verify');
        });
    });
    Route::group(['prefix' => 'uo/formfillup'], function () {
        Route::controller(UoFormFillUpController::class)->group(function () {
            //compulsory
            Route::get('/{tasks_id}/index', 'index')->name('uo.formfillup.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('uo.formfillup.ajaxlist');
            Route::get('/{id}/view', 'view')->name('uo.formfillup.view');
            Route::post('/{id}/revert', 'revert')->name('uo.formfillup.revert');
            Route::post('/{id}/forward', 'forward')->name('uo.formfillup.forward');
            Route::post('/{id}/reject', 'reject')->name('uo.formfillup.reject');
        });
    });
    Route::group(['prefix' => 'uo/file/submission'], function () {
        Route::controller(UoFileSubmissionController::class)->group(function () {
            //compulsory
            Route::get('/{tasks_id}/index', 'index')->name('uo.filesubmission.index');
            Route::post('/{tasks_id}/ajaxlist', 'ajaxlist')->name('uo.filesubmission.ajaxlist');
            Route::get('/{id}/view', 'view')->name('uo.filesubmission.view');
            Route::post('/{id}/revert', 'revert')->name('uo.filesubmission.revert');
            Route::post('/{id}/forward', 'forward')->name('uo.filesubmission.forward');
            Route::post('/{id}/reject', 'reject')->name('uo.filesubmission.reject');

            Route::post('file/{id}/submit', 'submit')->name('uo.filesubmission.submit');
        });
    });
});
