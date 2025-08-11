<?php

use App\Http\Controllers\Duties\ProformaController;
use Illuminate\Support\Facades\Route;
/*
* Duties ROUTES
*/

Route::group(['prefix' => 'duties', 'as' => 'duties.'], function () {
    // proforma
    Route::group(['prefix' => 'proforma'], function () {
        Route::controller(ProformaController::class)->group(function () {
            Route::get('/', 'create')->name('proforma.create');
            Route::post('list', 'ajaxlist')->name('proforma.ajaxlist');
            Route::post('/', 'storeStep1')->name('proforma.store1');
            Route::get('{id}/edit', 'edit')->name('proforma.edit');
            Route::put('{id}', 'update')->name('proforma.update');
            Route::delete('{id}', 'destroy')->name('proforma.destroy');
        });
    });
});
