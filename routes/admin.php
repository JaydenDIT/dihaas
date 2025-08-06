<?php

use App\Http\Controllers\Admin\ProcessController;
use App\Http\Controllers\Admin\ProcessTaskMappingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaskController;
use Illuminate\Support\Facades\Route;
/*
* ADMIN ROUTES
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Processes
    Route::group(['prefix' => 'process', 'as' => 'process.'], function () {
        Route::get('/', [ProcessController::class, 'index'])->name('index');
        Route::get('/create', [ProcessController::class, 'create'])->name('create');
        Route::post('/', [ProcessController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProcessController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProcessController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProcessController::class, 'destroy'])->name('destroy');
        Route::post('/ajax/list', [ProcessController::class, 'ajaxlist'])->name('ajaxlist');
    });
    //Role routes
    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('list', [RoleController::class, 'ajaxlist'])->name('ajaxlist');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('{id}', [RoleController::class, 'show'])->name('show');
        Route::put('{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    //Tasks routes
    Route::group(['prefix' => 'task', 'as' => 'task.'], function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [TaskController::class, 'update'])->name('update');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
        Route::post('/ajaxlist', [TaskController::class, 'ajaxlist'])->name('ajaxlist');
    });
    //Tasks routes
    Route::group(['prefix' => 'process-task-mapping', 'as' => 'processtaskmapping.'], function () {
        Route::get('/', [ProcessTaskMappingController::class, 'index'])->name('index');
        Route::post('/{id}/data', [ProcessTaskMappingController::class, 'fetchData'])->name('data');
        Route::post('/save', [ProcessTaskMappingController::class, 'saveMapping'])->name('save');
    });
});
