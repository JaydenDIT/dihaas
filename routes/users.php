<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'user'], function () {
    Route::get('/create-official-user', [UserController::class, 'createOfficialUser'])->name('user.createOfficialUser');
    Route::post('/create-official-user', [UserController::class, 'saveOfficialUser'])->name('user.saveOfficialUser');
    Route::get('/get-official-user', [UserController::class, 'getOfficialUsers'])->name('user.getOfficialUsers');
    Route::get('/get-citizen-user', [UserController::class, 'getCitizenUsers'])->name('user.getCitizenUsers');
    Route::get('/edit-official-user/{id}', [UserController::class, 'editOfficialUser'])->name('user.editOfficialUser');
    Route::post('/update-official-user', [UserController::class, 'updateOfficialUser'])->name('user.updateOfficialUser');
});
