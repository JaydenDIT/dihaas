<?php

use App\Http\Controllers\Duties\ProformaController;
use Illuminate\Support\Facades\Route;
/*
* Duties ROUTES
*/

Route::group(['prefix' => 'duties', 'as' => 'duties.'], function () {
    // proforma
    Route::group(['prefix' => 'proforma', 'as' => 'proforma.'], function () {
        Route::controller(ProformaController::class)->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('list', 'ajaxlist')->name('ajaxlist');
            Route::post('/', 'store')->name('store');
            Route::get('{id}', 'show')->name('show');
            Route::put('{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
        });
    });
});
