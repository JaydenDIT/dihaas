<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'user'], function () {
    Route::get('/create-official-user', [UserController::class, 'createOfficialUser'])->name('user.createOfficialUser');
    Route::post('/create-official-user', [UserController::class, 'saveOfficialUser'])->name('user.saveOfficialUser');
});
