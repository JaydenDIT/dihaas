<?php

use App\Http\Controllers\Duties\CitizenFormFillUpController;
use App\Http\Controllers\Duties\FamilyDetailController;
use App\Http\Controllers\Duties\ProformaController;
use App\Http\Controllers\Duties\UploadedDocumentController;
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
});
