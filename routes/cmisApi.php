<?php

use App\Http\Controllers\CMIS\CmisController;
use Illuminate\Support\Facades\Route;
/*
* Duties ROUTES
*/

Route::group(['prefix' => 'cmis', 'as' => 'cmis.'], function () {
    // proforma
    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
        Route::controller(CmisController::class)->group(function () {
            Route::get('employee/detail/{id}/ein', 'getEmployeeDetailByEIN')->name('employee.detail.ein');
            Route::get('post/all/{id}/dept_code', 'getPostByDeptCd')->name('post.dept_code');
        });
    });
});
