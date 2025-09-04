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
            Route::get('employee/detail/{id}/ein', 'getEmployeeDetailByEIN')->name('employee.detail.ein'); //cmis.api.employee.detail.ein
            Route::get('post/all/{id}/dept_code', 'getPostByDeptCd')->name('post.dept_code'); //cmis.api.post.dept_code
            Route::get('department/all/{id}/adm_cd', 'getDepartmentByAdmCd')->name('department.adm_cd'); //cmis.api.department.adm_cd
            Route::get('department/all/', 'getAllDepartments')->name('department.all'); //cmis.api.department.all
            Route::get('admin-department/all/', 'getAllAdminDepartments')->name('admin-department.all'); //cmis.api.admin-department.all
        });
    });
});
