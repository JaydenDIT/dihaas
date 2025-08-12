<?php

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
            Route::post('/', 'store')->name('proforma.store');
            Route::get('{id}/edit', 'edit')->name('proforma.edit');
            Route::post('{id}/update', 'update')->name('proforma.update');
            Route::delete('{id}', 'destroy')->name('proforma.destroy');
        });
    });
    Route::group(['prefix' => 'upload/document'], function () {
        Route::controller(UploadedDocumentController::class)->group(function () {
            Route::post('/', 'store')->name('upload.document.store');
            Route::get('/{id}', 'loadFile')->name('upload.document.load');
            Route::delete('{id}', 'destroy')->name('upload.document.destroy');
        });
    });
});
