<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthSuperAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Process\NotificationController;
use App\Http\Controllers\Process\ScreeningController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Process\AddressController;
use App\Http\Controllers\Authentication\CaptchaController;
use App\Http\Controllers\CmisVacancyController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeCmisController;
use App\Http\Controllers\PensionEmployeeController;
use App\Http\Controllers\FamilyMembersController;
use App\Http\Controllers\EmolumentController;
use App\Http\Controllers\EmpMilitaryServiceDetailsController;
use App\Http\Controllers\EmpAutonomousBodyServiceController;
use App\Http\Controllers\EmpQualifyingSvcController;
use App\Http\Controllers\EmpGovtDuesController;
use App\Http\Controllers\EmployeeFormControler;
use App\Http\Controllers\EmpFileUploadController;
use App\Http\Controllers\OthersFormDetailsController;
use App\Http\Controllers\NdcController;
use App\Http\Controllers\ExportFileController;
use App\Http\Controllers\DdoController;
use App\Http\Controllers\ESignController;
use App\Http\Controllers\PensionCalculationController;
use App\Http\Controllers\HodController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PensionCellController;
use App\Http\Controllers\AgController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DescriptiveRoleEditController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\Upload;
use App\Http\Controllers\VacancyUpdateController;
use App\Models\EmployeeCmis;
use App\Models\PensionEmployee;
use App\Models\PortalModel;
use App\Models\ProformaModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\FillUOController;
use App\Http\Controllers\CreatePdfController;
use App\Http\Controllers\GenerateUOController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SuccessStoriesController;
use App\Models\NotificationModel;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/phpinfo', function () {
//     phpinfo();
// });
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    echo 'Application cache has been cleared';
});

// Route::group(['middleware' => 'auth'], function () {

//     Route::get('/changePassword', [PasswordChangeController::class, 'showChangePasswordGet'])->name('changePasswordGet');
//     Route::post('/changePassword', [PasswordChangeController::class, 'changePasswordPost'])->name('changePasswordPost');
// });


/////////////////////API////////////////

///http://manipurtemp02.nic.in/cmis_api/public/api/get-employee-profile?ein=088323&token=b000e921eeb20a0d395e341dfcd6117a

/////////////////////////////////////////


// dashboard public
try {
    Route::get('/', function () {

        if (Route::has('login') && Auth::user() != null) {
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;

            $common_code = "";
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            $post = array();
            $totalApplicants = 0;
            $ProInCompleted = 0;
            $notYetVerified = 0;
            $verificationCompleted = 0;
            $underProcess = 0;
            $ProCompleted = 0;

            if ($role == 77) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();
                //dd($getUser1) ;

                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form    
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put('portal_name', $getSoftwareName);
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;


                //$status = [0, 6];
                $getStatus = ProformaModel::get()->where('uploaded_id', '=', $getUser->id)->first();
                if ($getStatus != null) {
                    if ($getStatus->status == 0) {
                        $status = "You have not submitted your application.... Incomplete Application";
                    } elseif ($getStatus->status == 1) {
                        $status = "Application Subitted";
                    } elseif ($getStatus->status == 2) {
                        $status = "Verified";
                    } elseif (($getStatus->status == 3 || $getStatus->status == 4 || $getStatus->status == 5)) {
                        $status = "Under Process";
                    } else {
                        $status = "Appointment Given";
                    }
                } else {
                    $status = "Not yet Applied";
                }

                //$notifications = NotificationModel::all();
                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy("created_at")->paginate(5);


                return view('admin/dashboard', compact('status', 'getUser1', 'notificationsArray', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notifications'));
            }

            if ($role == 1) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();
                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form    
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put('portal_name', $getSoftwareName);
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;


                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->where('dept_id', '=', $getUser->dept_id)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->where('dept_id', '=', $getUser->dept_id)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->where('dept_id', '=', $getUser->dept_id)->get(); //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::where('dept_id', '=', $getUser->dept_id)->get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->where('dept_id', '=', $getUser->dept_id)->get(); // removed list 


                $notYetVerified = ProformaModel::where('status', '=', 1)->where('dept_id', '=', $getUser->dept_id)->get(); // not yet activated

                $totalApplicants = count($getTotalApplicants); //Total

                $notYetVerified = count($notYetVerified); // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted); // no of retiree removed 

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy("created_at")->paginate(5);

                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }


            if ($role == 2 || $role == 3 || $role == 4 || $role == 9) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();
                //dd($getUser1);
                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form    
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put('portal_name', $getSoftwareName);
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;


                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->where('dept_id', '=', $getUser->dept_id)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->where('dept_id', '=', $getUser->dept_id)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->where('dept_id', '=', $getUser->dept_id)->get(); //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::where('dept_id', '=', $getUser->dept_id)->get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->where('dept_id', '=', $getUser->dept_id)->get(); // removed list 


                $notYetVerified = ProformaModel::where('status', '=', 1)->where('dept_id', '=', $getUser->dept_id)->get(); // not yet activated

                $totalApplicants = count($getTotalApplicants); //Total

                $notYetVerified = count($notYetVerified); // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted); // no of retiree removed 

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy("created_at")->paginate(5);
                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }


            if ($role == 999 || $role == 5 || $role == 6 || $role == 8) {
                $user_id1 = Auth::user()->id;
                $getUser1 = User::get()->where('id', $user_id1)->first();

                $getPortalName = PortalModel::where('id', 1)->first();
                //Portal name short form    
                $getProjectShortForm = $getPortalName->short_form_name;
                //Application long name
                $getSoftwareName = $getPortalName->software_name;
                //session()->put('portal_name', $getSoftwareName);
                //this is for footer
                $getDeptName = $getPortalName->department_name;
                $getGovtName = $getPortalName->govt_name;
                $getDeveloper = $getPortalName->developed_by;


                $status = [0, 6];
                $getUnderProcess = ProformaModel::where('status', '!=', $status)->get();
                $getIncomplete = ProformaModel::where('status', '=', 0)->get();
                $ProcessCompleted = ProformaModel::where('status', '=', 6)->get(); //1 IS SUBMITTED 2 IS VERIFIED 3 IS UO GENERATED

                $getTotalApplicants = ProformaModel::get();
                $ProInCompleted = count($getIncomplete);

                $verificationCompleted = ProformaModel::where('status', '=', 2)->get(); // removed list 


                $notYetVerified = ProformaModel::where('status', '=', 1)->get(); // not yet activated

                $totalApplicants = count($getTotalApplicants); //Total

                $notYetVerified = count($notYetVerified); // no of retiree not yet activate
                $verificationCompleted = count($verificationCompleted); // no of retiree removed 

                $underProcess = count($getUnderProcess);
                $ProCompleted = count($ProcessCompleted);

                $notificationsArray = NotificationModel::get()->toArray();
                $notifications = NotificationModel::orderBy("created_at")->paginate(5);

                return view('admin/dashboard', compact('getUser1', 'notificationsArray', 'ProInCompleted', 'getDeveloper', 'getGovtName', 'getDeptName', 'getSoftwareName', 'getProjectShortForm', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'notifications'));
            }
        } else {

            $getPortalName = PortalModel::where('id', 1)->first();
            //Portal name short form    
            $getProjectShortForm = $getPortalName->short_form_name;

            //this is for footer
            $getDeptName = $getPortalName->department_name;
            //return $getDeptName;
            $getGovtName = $getPortalName->govt_name;
            $getDeveloper = $getPortalName->developed_by;
            // $getCopyright = $getPortalName->copyright;


            $notificationsArray = NotificationModel::get()->toArray();
            $notifications = NotificationModel::orderBy("created_at")->paginate(5);


            // dd($notifications,$notificationsArray);

            //return view('welcome', compact('getCopyright','getDeveloper','getGovtName','getDeptName','getProjectShortForm', 'getSoftwareName', 'notYetVerified', 'verificationCompleted', 'totalApplicants', 'underProcess', 'ProCompleted', 'lastLoaddate',));
            return view('welcome', compact('notificationsArray', 'getDeveloper', 'getGovtName', 'getDeptName', 'getProjectShortForm', 'notifications',));
        }
    })->name('welcome');
} catch (Exception $e) {

    return response()->json([
        'status' => 0,
        'msg' => "Server not responding!!Pls see your internet connection!!or CMIS portal down",
        //'errors' => $e->getMessage()
    ]);
}


// Route::get('/test', function () {
//     return view('test');
// })->name('test');

Auth::routes();
// Admin Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/home', [HomeController::class, 'index'])->name('home');

Route::post('/reload-captcha', [CaptchaController::class, 'reloadCaptcha'])->name('reloadCaptcha');


/////dept login////
//verify otp
Route::post('smsLoginOTP', [App\Http\Controllers\Auth\LoginController::class, 'smsLoginOTP'])->name('smsLoginOTP'); //To verify username and mobile 
//verify resend otp
Route::post('/smsLoginOTPResend', [App\Http\Controllers\Auth\LoginController::class, 'smsLoginOTPResend'])->name('smsLoginOTPResend'); //To verify username and mobile 
Route::post('/authenticate', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('authenticate');
//this is for login of official the above

//////APPLICANT LOGIN REGISTER//////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
Route::get('loginApplicant', 'App\Http\Controllers\Auth\AuthController@index')->name('loginApplicant');
Route::post('postlogincitizen', 'App\Http\Controllers\Auth\AuthController@postlogincitizen')->name('postlogincitizen');
Route::get('postlogincitizen', 'App\Http\Controllers\Auth\AuthController@postlogincitizen')->name('postlogincitizen');


Route::get('retrieve_dept_register_user', 'App\Http\Controllers\Auth\AuthSuperAdminController@retrieve_dept_register_user')->name('retrieve_dept_register_user');

//citizen
Route::post('smsLoginCitizenOTP', [App\Http\Controllers\Auth\AuthController::class, 'smsLoginCitizenOTP'])->name('smsLoginCitizenOTP');
Route::get('smsLoginCitizenOTP', [App\Http\Controllers\Auth\AuthController::class, 'smsLoginCitizenOTP'])->name('smsLoginCitizenOTP');


Route::post('smsLoginCitizenOTPResend', [App\Http\Controllers\Auth\AuthController::class, 'smsLoginCitizenOTPResend'])->name('smsLoginCitizenOTPResend');
Route::post('/authenticateCitizen', [App\Http\Controllers\Auth\AuthController::class, 'authenticateCitizen'])->name('authenticate.citizen');

Route::post('smsLoginSuperadminOTP', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'smsLoginSuperadminOTP'])->name('smsLoginSuperadminOTP');

Route::post('smsLoginSuperadminOTPResend', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'smsLoginSuperadminOTPResend'])->name('smsLoginSuperadminOTPResend');
Route::post('/authenticateSuper', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'authenticateSuper'])->name('authenticate.super');
//superadmin
Route::get('superadmin', 'App\Http\Controllers\Auth\AuthSuperAdminController@index')->name('superadmin');
Route::post('postloginsuperadmin', 'App\Http\Controllers\Auth\AuthSuperAdminController@postloginsuperadmin')->name('postloginsuperadmin');
//Route::get('/superadminlogin', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'superadminlogin'])->name('superadminlogin');//To go to the superadmin login page

Route::get('register_new', 'App\Http\Controllers\Auth\AuthSuperAdminController@register')->name('register_new');
Route::post('register_new', 'App\Http\Controllers\Auth\AuthSuperAdminController@register')->name('register_new');

Route::post('post-register', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'postRegister'])->name('official-post-register');
//Route::get('post-register', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'postRegister'])->name('post-register');

Route::get('register_user_edit', 'App\Http\Controllers\Auth\AuthSuperAdminController@register_user_edit')->name('register_user_edit');
Route::post('register_user_edit', 'App\Http\Controllers\Auth\AuthSuperAdminController@register_user_edit')->name('register_user_edit');

Route::post('official-updateViewUser/{id}', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'indexOfficialUser'])->name('official-updateViewUser');
Route::get('official-updateViewUser/{id}', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'indexOfficialUser'])->name('official-updateViewUser');
Route::post('save-updateViewUser', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'updateOfficialUser'])->name('save.updateViewUser');
Route::get('save-updateViewUser', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'updateOfficialUser'])->name('save.updateViewUser');

Route::post('official-delete/{id}', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'deleteOfficialUser'])->name('official-delete');
Route::get('official-delete/{id}', [App\Http\Controllers\Auth\AuthSuperAdminController::class, 'deleteOfficialUser'])->name('official-delete');


Route::get('dashboard', [AuthController::class, 'dashboard']);
//Route::get('logout', 'App\Http\Controllers\Auth\AuthController@logout');


Route::controller(LoginController::class)->group(function () {

    //Route::get('password/forgot', 'forgotPassword')->name('password.forgot');
    Route::post('password/reset', 'passwordResetEmail')->name('password.reset');
    // Route::get('setting/profile', 'profileSetting')->name('setting.profile');
    //Route::post('setting/saveProfilePassword', 'saveProfilePassword')->name('setting.saveProfilePassword');
});

Route::controller(UserController::class)->group(function () {

    Route::get('password/forgot', 'forgotPassword')->name('password.forgot')->middleware('auth');
    //Route::post('password/reset', 'passwordResetEmail')->name('password.reset');

    Route::get('idproof/{doc_id}',  'userProofIdDoc')->name('userProofIdDoc')->middleware(['auth', 'noBack', 'noStore']);

    Route::get('setting/profile', 'profileSetting')->name('setting.profile')->middleware('auth');
    Route::post('setting/saveProfilePassword', 'saveProfilePassword')->name('setting.saveProfilePassword')->middleware('auth');


    Route::post('setting/saveProfileMain', 'saveProfileMain')->name('setting.saveProfileMain')->middleware(['auth', 'noBack', 'noStore']);
    Route::post('setting/saveProfileAddress', 'saveProfileAddress')->name('setting.saveProfileAddress')->middleware(['auth', 'noBack', 'noStore']);



    Route::get('view', 'officialViewUser')->name('official.officialViewUser')->middleware(['auth', 'role:999', 'noBack', 'noStore']);
    Route::get('viewLog', 'auditLogView')->name('official.auditLogView')->middleware(['auth', 'role:999', 'noBack', 'noStore']);
});


Route::controller(AuthSuperAdminController::class)->group(function () {

    Route::get('password/forgot', 'forgotPassword')->name('password.forgot');
    Route::post('password/reset', 'passwordResetEmail')->name('password.reset');
});
Route::controller(AuthController::class)->group(function () {

    Route::get('password/forgot', 'forgotPassword')->name('password.forgot');
    Route::post('password/reset', 'passwordResetEmail')->name('password.reset');
});

///////////////////////////Login user is SUPERADMIN and Password is admin@123//////////////////////////////////

//Route::post('post-login', 'App\Http\Controllers\Auth\AuthSuperAdminController@postLogin');

Route::get('dashboard', [AuthSuperAdminController::class, 'dashboard']);
Route::get('logout', 'App\Http\Controllers\Auth\AuthSuperAdminController@logout');

//////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/grades', 'App\Http\Controllers\GradeController@index');
Route::get('/grade/create', 'App\Http\Controllers\GradeController@create');
Route::post('/grade/create', 'App\Http\Controllers\GradeController@store');
Route::get('/grade/{id}/edit', 'App\Http\Controllers\GradeController@edit');
Route::put('/grade/{id}/edit', 'App\Http\Controllers\GradeController@update');
Route::delete('/grade/delete/{id}', 'App\Http\Controllers\GradeController@destroy');

Route::post('/grades/delete-selected', 'App\Http\Controllers\GradeController@deleteSelected')->name('grades.deleteSelected');
Route::get('/grades/download-pdf', 'App\Http\Controllers\GradeController@downloadPDF')->name('grades.downloadPDF');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/departments', 'App\Http\Controllers\DepartmentController@index');
Route::get('/department/create', 'App\Http\Controllers\DepartmentController@create');
Route::post('/department/create', 'App\Http\Controllers\DepartmentController@store');
Route::get('/department/{id}/edit', 'App\Http\Controllers\DepartmentController@edit');
Route::put('/department/{id}/edit', 'App\Http\Controllers\DepartmentController@update');
Route::delete('/department/delete/{id}', 'App\Http\Controllers\DepartmentController@destroy');

Route::get('/states', 'App\Http\Controllers\StateController@index');
Route::get('/state/create', 'App\Http\Controllers\StateController@create');
Route::post('/state/create', 'App\Http\Controllers\StateController@store');
Route::get('/state/{id}/edit', 'App\Http\Controllers\StateController@edit');
Route::put('/state/{id}/edit', 'App\Http\Controllers\StateController@update');
Route::delete('/state/delete/{id}', 'App\Http\Controllers\StateController@destroy');

Route::get('/eligibilities', 'App\Http\Controllers\EligibilityController@index');
Route::get('/eligibility/create', 'App\Http\Controllers\EligibilityController@create');
Route::post('/eligibility/create', 'App\Http\Controllers\EligibilityController@store');
Route::get('/eligibility/{id}/edit', 'App\Http\Controllers\EligibilityController@edit');
Route::put('/eligibility/{id}/edit', 'App\Http\Controllers\EligibilityController@update');
Route::delete('/eligibility/delete/{id}', 'App\Http\Controllers\EligibilityController@destroy');

Route::get('/vpercent', 'App\Http\Controllers\VPercentageController@index');
Route::get('/vpercentage/create', 'App\Http\Controllers\VPercentageController@create');
Route::post('/vpercentage/create', 'App\Http\Controllers\VPercentageController@store');
Route::get('/vpercentage/{id}/edit', 'App\Http\Controllers\VPercentageController@edit');
Route::put('/vpercentage/{id}/edit', 'App\Http\Controllers\VPercentageController@update');
Route::delete('/vpercentage/delete/{id}', 'App\Http\Controllers\VPercentageController@destroy');


Route::get('/designations', 'App\Http\Controllers\DesignationController@index');
Route::get('/designation/create', 'App\Http\Controllers\DesignationController@create');
Route::post('/designation/create', 'App\Http\Controllers\DesignationController@store');
Route::get('/designation/{id}/edit', 'App\Http\Controllers\DesignationController@edit');
Route::put('/designation/{id}/edit', 'App\Http\Controllers\DesignationController@update');
Route::delete('/designation/delete/{id}', 'App\Http\Controllers\DesignationController@destroy');


Route::get('/educations', 'App\Http\Controllers\EducationController@index');
Route::get('/education/create', 'App\Http\Controllers\EducationController@create');
Route::post('/education/create', 'App\Http\Controllers\EducationController@store');
Route::get('/education/{id}/edit', 'App\Http\Controllers\EducationController@edit');
Route::put('/education/{id}/edit', 'App\Http\Controllers\EducationController@update');
Route::delete('/education/delete/{id}', 'App\Http\Controllers\EducationController@destroy');
Route::get('/districts', 'App\Http\Controllers\DistrictController@index');
Route::get('/district/create', 'App\Http\Controllers\DistrictController@create');
Route::post('/district/create', 'App\Http\Controllers\DistrictController@store');
Route::get('/district/{id}/edit', 'App\Http\Controllers\DistrictController@edit');
Route::put('/district/{id}/edit', 'App\Http\Controllers\DistrictController@update');
Route::delete('/district/delete/{id}', 'App\Http\Controllers\DistrictController@destroy');

Route::get('/subdivisions', 'App\Http\Controllers\SubdivisionController@index');
Route::get('/subdivision/create', 'App\Http\Controllers\SubdivisionController@create');
Route::post('/subdivision/create', 'App\Http\Controllers\SubdivisionController@store');
Route::get('/subdivision/{id}/edit', 'App\Http\Controllers\SubdivisionController@edit');
Route::put('/subdivision/{id}/edit', 'App\Http\Controllers\SubdivisionController@update');
Route::delete('/subdivision/delete/{id}', 'App\Http\Controllers\SubdivisionController@destroy');

Route::get('/roles', 'App\Http\Controllers\RoleController@index');
Route::get('/role/create', 'App\Http\Controllers\RoleController@create');
Route::post('/role/create', 'App\Http\Controllers\RoleController@store');
Route::get('/role/{id}/edit', 'App\Http\Controllers\RoleController@edit');
Route::put('/role/{id}/edit', 'App\Http\Controllers\RoleController@update');
Route::delete('/role/delete/{id}', 'App\Http\Controllers\RoleController@destroy');

//Routes for System Configuration
Route::get('/applicationnumbers', 'App\Http\Controllers\ApplicationnumberController@index');
Route::get('/applicationnumber/create', 'App\Http\Controllers\ApplicationnumberController@create');
Route::post('/applicationnumber/create', 'App\Http\Controllers\ApplicationnumberController@store');
Route::get('/applicationnumber/{id}/edit', 'App\Http\Controllers\ApplicationnumberController@edit');
Route::put('/applicationnumber/{id}/edit', 'App\Http\Controllers\ApplicationnumberController@update');
Route::delete('applicationnumber/delete/{id}', 'App\Http\Controllers\ApplicationnumberController@destroy');

Route::get('/uonomenclatures', 'App\Http\Controllers\UoNomenclatureController@index');
Route::get('/uonomenclature/create', 'App\Http\Controllers\UoNomenclatureController@create');
Route::post('/uonomenclature/create', 'App\Http\Controllers\UoNomenclatureController@store');
Route::get('/uonomenclature/{id}/edit', 'App\Http\Controllers\UoNomenclatureController@edit');
Route::put('/uonomenclature/{id}/edit', 'App\Http\Controllers\UoNomenclatureController@update');
Route::delete('uonomenclature/delete/{id}', 'App\Http\Controllers\UoNomenclatureController@destroy');

Route::get('/files_name', 'App\Http\Controllers\FilesToUploadController@index');
Route::get('/file/create', 'App\Http\Controllers\FilesToUploadController@create');
Route::post('/file/create', 'App\Http\Controllers\FilesToUploadController@store');
Route::get('/file/{id}/edit', 'App\Http\Controllers\FilesToUploadController@edit');
Route::put('/file/{id}/edit', 'App\Http\Controllers\FilesToUploadController@update');
Route::delete('file/delete/{id}', 'App\Http\Controllers\FilesToUploadController@destroy');

Route::get('/remarksrejects', 'App\Http\Controllers\RemarksRejectController@index');
Route::get('/remarksreject/create', 'App\Http\Controllers\RemarksRejectController@create');
Route::post('/remarksreject/create', 'App\Http\Controllers\RemarksRejectController@store');
Route::get('/remarksreject/{id}/edit', 'App\Http\Controllers\RemarksRejectController@edit');
Route::put('/remarksreject/{id}/edit', 'App\Http\Controllers\RemarksRejectController@update');
Route::delete('/remarksreject/delete/{id}', 'App\Http\Controllers\RemarksRejectController@destroy');

Route::get('/remarksapproves', 'App\Http\Controllers\RemarksApproveController@index');
Route::get('/remarksapprove/create', 'App\Http\Controllers\RemarksApproveController@create');
Route::post('/remarksapprove/create', 'App\Http\Controllers\RemarksApproveController@store');
Route::get('/remarksapprove/{id}/edit', 'App\Http\Controllers\RemarksApproveController@edit');
Route::put('/remarksapprove/{id}/edit', 'App\Http\Controllers\RemarksApproveController@update');
Route::delete('/remarksapprove/delete/{id}', 'App\Http\Controllers\RemarksApproveController@destroy');

Route::get('/dpauthorities', 'App\Http\Controllers\DPSigningAuthoritiesController@index');
Route::get('/dpauthority/create', 'App\Http\Controllers\DPSigningAuthoritiesController@create');
Route::post('/dpauthority/create', 'App\Http\Controllers\DPSigningAuthoritiesController@store');
Route::get('/dpauthority/{id}/edit', 'App\Http\Controllers\DPSigningAuthoritiesController@edit');
Route::put('/dpauthority/{id}/edit', 'App\Http\Controllers\DPSigningAuthoritiesController@update');
Route::delete('/dpauthority/delete/{id}', 'App\Http\Controllers\DPSigningAuthoritiesController@destroy');


Route::get('/deptauthorities', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@index');
Route::get('/deptauthority/create', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@create');
Route::post('/deptauthority/create', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@store');
Route::get('/deptauthority/{id}/edit', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@edit');
Route::put('/deptauthority/{id}/edit', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@update');
Route::delete('/deptauthority/delete/{id}', 'App\Http\Controllers\DepartmentSigningAuthoritiesController@destroy');


Route::get('/viewEsignByDP', [HomeController::class, 'viewEsignByDP'])->name('viewEsignByDP');

Route::post('/viewEsignByDPSearch', [HomeController::class, 'viewEsignByDPSearch'])->name('viewEsignByDPSearch');

Route::post('/viewEsignByDPDeptSelect', [SubmitController::class, 'viewEsignByDPDeptSelect'])->name('viewEsignByDPDeptSelect');
//above code register routes inserted by ANAND////////////////////////////////////////////////////////////////////////////////////////
Route::get('/viewEsignByDepartmentSigningAuthority', [HomeController::class, 'viewEsignByDepartmentSigningAuthority'])->name('viewEsignByDepartmentSigningAuthority');

Route::post('/viewEsignByDepartmentSigningAuthoritySearch', [HomeController::class, 'viewEsignByDepartmentSigningAuthoritySearch'])->name('viewEsignByDepartmentSigningAuthoritySearch');












// DDO-ASSIST route group
Route::prefix("ddo-assist")->group(function () {

    Route::post('/transferFromDPAssistToAnyDepartment/{id}', [HomeController::class, 'transferFromDPAssistToAnyDepartment'])->name('transferFromDPAssistToAnyDepartment');
    Route::get('/transferFromDPAssistToAnyDepartment/{id}', [HomeController::class, 'transferFromDPAssistToAnyDepartment'])->name('transferFromDPAssistToAnyDepartment');
    
    Route::get('/transfer_applicant_By_DPAssist', [HomeController::class, 'transfer_applicant_By_DPAssist'])->name('transfer_applicant_By_DPAssist');
    Route::get('/viewTransferListByHodAssistant', [HomeController::class, 'viewTransferListByHodAssistant'])->name('viewTransferListByHodAssistant');
    
    Route::post('/viewTransferListByHodAssistantSearch', [HomeController::class, 'viewTransferListByHodAssistantSearch'])->name('viewTransferListByHodAssistantSearch');
    Route::get('/viewTransferListByHod', [HomeController::class, 'viewTransferListByHod'])->name('viewTransferListByHod');

    Route::post('/updateTransferStatus',  [HomeController::class, 'updateTransferStatus'])->name('updateTransferStatus');
    
    Route::get('/vacancy_update', [VacancyUpdateController::class, 'vacancyUpdate'])->name('vacancy_update');


    // delete docs

    // get sub division
    Route::get('/get-sub-division/{districtId}', [HomeController::class, 'getSubDivsion'])->name('get-sub-division');
    // get assembly constituency
    Route::get('/get-assem-const/{districtId}', [HomeController::class, 'getAssemblyConst'])->name('get-assem-const');
    // get get pay scale dependent to pay commission



    // Upload Employee Files

    //below is for upload files and for viewing submitted applicants list
    Route::get('/viewStartEmp', [HomeController::class, 'viewStartEmp'])->name('viewStartEmp');
    Route::post('/viewStartEmpSearch', [HomeController::class, 'viewStartEmpSearch'])->name('viewStartEmpSearch');
    Route::get('/viewStartEmp/downloadPDFApplView', 'App\Http\Controllers\HomeController@downloadPDFApplView')->name('viewStartEmp.downloadPDFApplView');



    //verify and forward
    Route::get('/viewEmpSubmitted', [HomeController::class, 'viewEmpSubmitted'])->name('viewEmpSubmitted');
    Route::post('/viewEmpSubmittedSearch', [HomeController::class, 'viewEmpSubmittedSearch'])->name('viewEmpSubmittedSearch');

    //HOD forward applicants list
    Route::get('/viewForwardEmp', [HomeController::class, 'viewForwardEmp'])->name('viewForwardEmp');
    Route::post('/viewForwardEmpSearch', [HomeController::class, 'viewForwardEmpSearch'])->name('viewForwardEmpSearch');
    Route::get('/viewForwardEmp/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFApplView')->name('viewForwardEmp.downloadPDFApplView');

    //AD Assistant forward applicants list
    Route::get('/viewForwardToADAssist', [HomeController::class, 'viewForwardToADAssist'])->name('viewForwardToADAssist');
    Route::post('/viewForwardToADAssistSearch', [HomeController::class, 'viewForwardToADAssistSearch'])->name('viewForwardToADAssistSearch');
    Route::get('/viewForwardToADAssist/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFApplView')->name('viewForwardToADAssist.downloadPDFApplView');

    //AD Nodal forward applicants list
    Route::get('/viewForwardToADNodal', [HomeController::class, 'viewForwardToADNodal'])->name('viewForwardToADNodal');
    Route::post('/viewForwardToADNodalSearch', [HomeController::class, 'viewForwardToADNodalSearch'])->name('viewForwardToADNodalSearch');
    Route::get('/viewForwardToADNodal/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFApplView')->name('viewForwardToADNodal.downloadPDFApplView');

    //DP Assistant forward applicants list
    //select Dept
    Route::post('/selectDeptByDPAssist', [HomeController::class, 'selectDeptByDPAssist'])->name('selectDeptByDPAssist');
    Route::get('/selectDeptByDPAssist', [HomeController::class, 'selectDeptByDPAssist'])->name('selectDeptByDPAssist');
    Route::post('/selectDeptByDPAssistSearch', [HomeController::class, 'selectDeptByDPAssistSearch'])->name('selectDeptByDPAssistSearch');
    ////Download PDF

    Route::get('/view-file/{filename}',  [HomeController::class, 'viewFileByADNodal'])->name('viewFileForwardByADNodal');
    Route::post('/forward-selected-eins', [HomeController::class, 'forwardSelectedEINs'])->name('forwardSelectedEINs');

    Route::get('/fill_uo_update/{id}', [FillUOController::class, 'update_view'])->name('fill_uo_update');
    Route::post('/update_uo/{id}', [FillUOController::class, 'fillUOUpdate'])->name('update_uo');
    Route::get('/update_uo/{id}', [FillUOController::class, 'fillUOUpdate'])->name('update_uo');

    //Route::get('/submit-form/download-pdf', 'App\Http\Controllers\SubmitController@downloadPDF')->name('submit-form.downloadPDF');

    Route::get('/selectDeptByDPAssist/download-pdf', 'App\Http\Controllers\HomeController@downloadPDF')->name('selectDeptByDPAssist.downloadPDF');

    Route::get('/selectDeptByDPAssist/download-pdf', 'App\Http\Controllers\HomeController@downloadPDF')->name('selectDeptByDPAssist.downloadPDFStatusByDP');
    ////////////////////////////////////////
    Route::post('/forward-selected-einsADNodalToDPAssist', [HomeController::class, 'forwardSelectedEINsADNodalToDPAssist'])->name('forwardSelectedEINsADNodalToDPAssist');
    //below is not use but still in form list

    //Route::get('/viewForwardToDPAssist', [HomeController::class, 'viewForwardToDPAssist'])->name('viewForwardToDPAssist');
    // Route::post('/viewForwardToDPAssistSearch', [HomeController::class, 'viewForwardToDPAssistSearch'])->name('viewForwardToDPAssistSearch');

    // Route::get('/viewForwardToDPNodal', [HomeController::class, 'viewForwardToDPNodal'])->name('viewForwardToDPNodal');
    // Route::post('/viewForwardToDPNodalSearch', [HomeController::class, 'viewForwardToDPNodalSearch'])->name('viewForwardToDPNodalSearch');
    ///////////////THE ABOVE 2 PART ARE NOT USED///////////////////

    Route::post('/selectDeptByDPNodal', [HomeController::class, 'selectDeptByDPNodal'])->name('selectDeptByDPNodal');
    Route::get('/selectDeptByDPNodal', [HomeController::class, 'selectDeptByDPNodal'])->name('selectDeptByDPNodal');

    ////Download PDF

    Route::get('/submit_form/download-pdfNODAL', 'App\Http\Controllers\SubmitController@downloadPDFNODAL')->name('submit_form.downloadPDFNODAL');

    Route::get('/selectDeptByDPNodal/download-pdfNODAL', 'App\Http\Controllers\HomeController@downloadPDFNODAL')->name('selectDeptByDPNodal.downloadPDFNODAL');
    ////////////////////////////////////////

    Route::get('/selectDeptByDPApprove', [HomeController::class, 'selectDeptByDPApprove'])->name('selectDeptByDPApprove');
    Route::post('/selectDeptByDPApproveSearch', [HomeController::class, 'selectDeptByDPApproveSearch'])->name('selectDeptByDPApproveSearch');

    Route::get('/selectDeptByDPApproveDept', [HomeController::class, 'selectDeptByDPApproveDept'])->name('selectDeptByDPApproveDept');
    Route::post('/selectDeptByDPApproveDeptSearch', [HomeController::class, 'selectDeptByDPApproveDeptSearch'])->name('selectDeptByDPApproveDeptSearch');

    // Route::get('/submit_form/download-pdfNODALVacancy', 'App\Http\Controllers\SubmitController@downloadPDFNODALVacancy')->name('submit_form.downloadPDFNODALVacancy');

    ////Download PDF

    Route::get('/submit_form/download-pdfApproved', 'App\Http\Controllers\SubmitController@downloadPDFApproved')->name('submitform.downloadPDFApproved');

    Route::get('/selectDeptByDPApprove/download-pdfApproved', 'App\Http\Controllers\HomeController@downloadPDFApproved')->name('selectDeptByDPApprove.downloadPDFApproved');
    ////////////////////////////////////////


    //below is for Reverted List view for HOD Assistant
    Route::get('/viewRejectedListHODAssist', [HomeController::class, 'viewRejectedListHODAssist'])->name('viewRejectedListHODAssist');
    Route::post('/viewRejectedListHODAssistSearch', [HomeController::class, 'viewRejectedListHODAssistSearch'])->name('viewRejectedListHODAssistSearch');
    Route::get('/viewRejectedListHODAssist/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFReject')->name('viewRejectedListHODAssist.downloadPDFReject');

    //below is for Reverted List view for HOD
    Route::get('/viewRejectedListHOD', [HomeController::class, 'viewRejectedListHOD'])->name('viewRejectedListHOD');
    Route::post('/viewRejectedListHODSearch', [HomeController::class, 'viewRejectedListHODSearch'])->name('viewRejectedListHODSearch');
    Route::get('/viewRejectedListHOD/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFRejectHOD')->name('viewRejectedListHOD.downloadPDFRejectHOD');

    //below is for Reverted List view for HOD
    Route::get('/viewRevertedListADAssist', [HomeController::class, 'viewRevertedListADAssist'])->name('viewRevertedListADAssist');
    Route::post('/viewRevertedListADAssistSearch', [HomeController::class, 'viewRevertedListADAssistSearch'])->name('viewRevertedListADAssistSearch');
    Route::get('/viewRevertedListADAssist/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFRejectADAssist')->name('viewRevertedListADAssist.downloadPDFRejectADAssist');


    //below is for Reverted List view for HOD
    Route::get('/viewRevertedListADNodal', [HomeController::class, 'viewRevertedListADNodal'])->name('viewRevertedListADNodal');
    Route::post('/viewRevertedListADNodalSearch', [HomeController::class, 'viewRevertedListADNodalSearch'])->name('viewRevertedListADNodalSearch');
    Route::get('/viewRevertedListADNodal/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFRejectADNodal')->name('viewRevertedListADNodal.downloadPDFRejectADNodal');



    //below is for Reverted List view for DP

    Route::post('/viewRevertedListDP', [HomeController::class, 'viewRevertedListDP'])->name('viewRevertedListDP');
    Route::get('/viewRevertedListDP', [HomeController::class, 'viewRevertedListDP'])->name('viewRevertedListDP');
    //Route::post('/viewRevertedListDPSearch', [HomeController::class, 'viewRevertedListDPSearch'])->name('viewRevertedListDPSearch');
    Route::get('/viewRevertedListDP/download-pdf', 'App\Http\Controllers\HomeController@downloadPDFRejectDP')->name('viewRevertedListDP.downloadPDFRejectDP');

    Route::get('/submit_form/download-pdfDP', 'App\Http\Controllers\SubmitController@downloadPDFRejectDP')->name('submit_form.downloadPDFRejectDP');

    Route::get('/viewRevertedListDP/download-pdfDP', 'App\Http\Controllers\HomeController@downloadPDFRejectDP')->name('viewRevertedListDP.downloadPDFRejectDP');

    // downloadPDFRejectDP

    //viewRevertedListDP
    ////////////////////////////////////////////status FOR ALL AS PER DEPT///
    Route::post('/viewFileStatus', [HomeController::class, 'viewFileStatus'])->name('viewFileStatus');
    Route::get('/viewFileStatus', [HomeController::class, 'viewFileStatus'])->name('viewFileStatus');

    Route::post('/viewFileStatusSearch', [HomeController::class, 'viewFileStatusSearch'])->name('viewFileStatusSearch');
    Route::get('/viewFileStatusSearch', [HomeController::class, 'viewFileStatusSearch'])->name('viewFileStatusSearch');

    Route::get('/viewFileStatus/downloadPDFStatus', 'App\Http\Controllers\HomeController@downloadPDFStatus')->name('viewFileStatus.downloadPDFStatus');

    ///////////////////////////////DP by DEpt
    Route::post('/viewFileStatusByDPDept', [HomeController::class, 'viewFileStatusByDPDept'])->name('viewFileStatusByDPDept');
    Route::get('/viewFileStatusByDPDept', [HomeController::class, 'viewFileStatusByDPDept'])->name('viewFileStatusByDPDept');
    ////////////////////////////////////////////
    ////////////////////DP Assistant Select Dept and display////////////////////////

    ////////////Screening  Table 
    Route::post('/viewSeniorityStatus', [HomeController::class, 'viewSeniorityStatus'])->name('viewSeniorityStatus');
    Route::get('/viewSeniorityStatus', [HomeController::class, 'viewSeniorityStatus'])->name('viewSeniorityStatus');
    ///////////////Screening Search
    Route::post('/viewSeniorityStatusSearch', [HomeController::class, 'viewSeniorityStatusSearch'])->name('viewSeniorityStatusSearch');
    Route::get('/viewSeniorityStatusSearch', [HomeController::class, 'viewSeniorityStatusSearch'])->name('viewSeniorityStatusSearch');
    /////////////////Department Select 

    Route::post('/seniorityDeptSelect', [HomeController::class, 'seniorityDeptSelect'])->name('seniorityDeptSelect');
    Route::get('/seniorityDeptSelect', [HomeController::class, 'seniorityDeptSelect'])->name('seniorityDeptSelect');
    //////Dowload Pdf for Screening Report
    Route::get('/viewSeniorityStatus/downloadseniorityPDF', 'App\Http\Controllers\HomeController@downloadseniorityPDF')->name('viewSeniorityStatus.downloadseniorityPDF');

   
  
    ///////////////////////////////////////////////////////

    Route::post('/submitForm', [SubmitController::class, 'submitForm'])->name('submitForm');

    Route::post('/submit-form', [SubmitController::class, 'submitFormApprove'])->name('submit-form');

    Route::post('/submit_form', [SubmitController::class, 'submitFormNODAL'])->name('submitFormNODAL');

    Route::post('/submit-formDP', [SubmitController::class, 'submitFormDP'])->name('submitFormDP');

    Route::post('/submitApplicant', [SubmitController::class, 'submitFormApplicant'])->name('submitApplicant');


    ///////////////////////////////////////////////////////////

    ///////////////////////APPLICANT TASK/////////////
    Route::get('/viewStatusApplicant', [HomeController::class, 'viewStatusApplicant'])->name('viewStatusApplicant');

    Route::get('/viewApplicantDetailsUpdate/{id}', [HomeController::class, 'viewApplicantUpdate'])->name('viewApplicantDetailsUpdate');

    Route::post('update_proforma_self', [HomeController::class, 'updateself'])->name('update_proforma_self');

    Route::get('/discard-applicant/{ein}', [HomeController::class, 'discardApplicant'])->name('discard-applicant');

    //Update Family Details
    Route::get('/view-family-applicant-update', [FamilyMembersController::class, 'applicantupdate'])->name('view-family-applicant-update');
    Route::post('/save-family-applicant-update', [FamilyMembersController::class, 'applicantstoreLeft'])->name('save-family-applicant-update');
    Route::get('/delete-family-applicant-update/{id}', [FamilyMembersController::class, 'applicantdestroyUpdate'])->name('delete-family-applicant-update');

    Route::get('/create-applicant-self-update', [Upload::class, 'createApplicantUpdate'])->name('create-applicant-self-update');
    Route::post('/upload-applicant-self-update', [Upload::class, 'fileApplicantUpdate'])->name('upload-applicant-self-update');
    Route::post('/upload-applicant-self-submit', [Upload::class, 'finalFileUploadSubmitself'])->name('upload-applicant-self-submit');
    Route::post('/upload-applicant-self-delete', [Upload::class, 'deleteFileUploadSubmitself'])->name('upload-applicant-self-delete');



    Route::get('/other_form_applicant_update', [OthersFormDetailsController::class, 'applicantUpdate'])->name('other_form_applicant_update');
    Route::get('/submit-applicant-update', [OthersFormDetailsController::class, 'submitApplicantUpdate'])->name('submit-applicant-update');



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //File upload DIHAS

    Route::get('/create-applicant-files', [Upload::class, 'createForm'])->name('create-applicant-files');
    Route::post('/upload-applicant-files', [Upload::class, 'fileUpload'])->name('upload-applicant-files');
    Route::post('/upload-applicant-files-draft', [Upload::class, 'fileUploadSubmit'])->name('upload-applicant-files-draft');
    Route::post('/upload-applicant-files-fdelete', [Upload::class, 'deleteFileUpload'])->name('upload-applicant-files-fdelete');


    //files upload backlog   
    Route::get('/create-backlog-files', [Upload::class, 'createFormbacklog'])->name('create-backlog-files');
    Route::post('/upload-backlog-files', [Upload::class, 'fileUploadbacklog'])->name('upload-backlog-files');
    Route::post('/upload-applicant-files-draftbacklog', [Upload::class, 'fileUploadSubmitbacklog'])->name('upload-applicant-files-draftbacklog');
    Route::post('/upload-applicant-files-fdeletebacklog', [Upload::class, 'deleteFileUploadbacklog'])->name('upload-applicant-files-fdeletebacklog');

    //second applicant
    Route::get('/create-applicant-files2ndAppl', [Upload::class, 'createForm2ndAppl'])->name('create-applicant-files2ndAppl');
    Route::post('/upload-applicant-files2ndAppl', [Upload::class, 'fileUpload2ndAppl'])->name('upload-applicant-files2ndAppl');
    Route::post('/upload-applicant-files-draft2ndAppl', [Upload::class, 'fileUploadSubmit2ndAppl'])->name('upload-applicant-files-draft2ndAppl');
    Route::post('/upload-applicant-files-fdelete2ndAppl', [Upload::class, 'deleteFileUpload2ndAppl'])->name('upload-applicant-files-fdelete2ndAppl');

    //THIS IS THE ONE WORKS
    //Update File upload DIHAS
    Route::get('/create-applicant-files-dihas', [Upload::class, 'createFormUpdate'])->name('create-applicant-files-dihas');
    Route::post('/create-applicant-files-dihas', [Upload::class, 'createFormUpdate'])->name('create-applicant-files-dihas');
    Route::post('/upload-applicant-files-update', [Upload::class, 'fileUploadUpdate'])->name('upload-applicant-files-update');
    Route::get('/upload-applicant-files-update', [Upload::class, 'fileUploadUpdate'])->name('upload-applicant-files-update');
    Route::post('/upload-applicant-files-submit', [Upload::class, 'finalFileUploadSubmit'])->name('upload-applicant-files-submit');
    Route::post('/upload-applicant-files-delete', [Upload::class, 'deleteFileUploadSubmit'])->name('upload-applicant-files-delete');


    //view for uploaded applicants
    Route::get('/uploaded-applicant-files', [Upload::class, 'index'])->name('uploaded-applicant-files');

    Route::get('/viewFile/{doc_id}', [Upload::class, 'viewFile'])->name('viewFile');

    Route::get('/downLoad/{doc_id}', [Upload::class, 'downloadFile'])->name('viewFile');


    // discard start employee
    Route::get('/discard-started-employee/{ein}', [HomeController::class, 'discardStartEmp'])->name('discard-started-employee');

    // not yet activated list 
    Route::get('/not-yet-activated', [HomeController::class, 'getNotYetActivateEmpList'])->name('not-yet-activated');
    // activated
    Route::get('/activated', [HomeController::class, 'getActivatedEmpList'])->name('activated');
    // removed
    Route::get('/removed', [HomeController::class, 'getRemovedEmpList'])->name('removed');
    // discarded removed emp
    Route::get('/discard-removed-emp/{ein}', [HomeController::class, 'discardedRemovedEmp'])->name('discard-removed-emp');
    // discard activated emp
    Route::get('/discard-activated-emp/{ein}', [HomeController::class, 'discardedActiveEmp'])->name('discard-activated-emp');

    // removed cmis emp list
    Route::get('/removed-cmis-emp/{ein}', [HomeController::class, 'removedEmp'])->name('removed-cmis-emp');
    // activate emp from cmis emp list
    Route::get('/activate-cmis-emp/{ein}', [HomeController::class, 'activateEmp'])->name('activate-cmis-emp');
    // start activated cmis employees
    Route::get('/start-active-emp/{ein}', [HomeController::class, 'startActivatedEmp'])->name('start-active-emp');

    // view descriptve role

    // Proforma data entry 

    Route::get('/retrieve_dept', [HomeController::class, 'retrieve_dept'])->name('retrieve_dept');

    //Route::get('/desig_id', [HomeController::class, 'retrieve_dept'])->name('retrieve_dept');

    Route::get('/enterProformaDetails', [HomeController::class, 'viewForm1'])->name('enterProformaDetails');
    Route::post('/enterProformaDetails', [HomeController::class, 'viewForm1'])->name('enterProformaDetails');
    //Route::get('/save-proforma-details', [HomeController::class, 'create'])->name('save-proforma-details');
    Route::post('/save-proforma-details', [HomeController::class, 'store'])->name('save-proforma-details');
    Route::post('/removeSessionEIN', [HomeController::class, 'removeSessionEIN'])->name('removeSessionEIN');

    Route::post('/checkein', [HomeController::class, 'checkEin'])->name('checkein');
    Route::post('/checkemail', [HomeController::class, 'checkEmail'])->name('checkemail');

    Route::post('update_proforma', [HomeController::class, 'update'])->name('update_proforma');
    Route::get('update_proforma', [HomeController::class, 'update'])->name('update_proforma');

    Route::post('discard_Changes', [HomeController::class, 'discardChanges'])->name('discard_Changes');



    Route::get('/enterBacklogDetails', [HomeController::class, 'viewFormBacklog'])->name('enterBacklogDetails');
    Route::post('/enterBacklogDetails', [HomeController::class, 'viewFormBacklog'])->name('enterBacklogDetails');
    //Route::get('/save-proforma-details-backlog', [HomeController::class, 'create1'])->name('save-proforma-details-backlog');
    Route::post('/save-proforma-details-backlog', [HomeController::class, 'store1'])->name('save-proforma-details-backlog');


    Route::get('/viewchangeApplicant', [HomeController::class, 'viewChangeApplicant'])->name('viewchangeApplicant');
    Route::post('/viewchangeApplicant', [HomeController::class, 'viewChangeApplicant'])->name('viewchangeApplicant');

    Route::get('/changeApplicant/{id}', [HomeController::class, 'show'])->name('changeApplicant');
    //Route to save second applicant_name 
    Route::post('/save_applicant', [HomeController::class, 'saveApplicant'])->name('save_applicant');

    Route::post('/update_second_applicant_data', [HomeController::class, 'updateSecondApplicantData'])->name('update_second_applicant_data');
    Route::get('/update_second_applicant_data', [HomeController::class, 'updateSecondApplicantData'])->name('update_second_applicant_data');

    //second appl
    //Route::get('/save-proforma-details2ndAppl', [HomeController::class, 'create2ndAppl'])->name('save-proforma-details2ndAppl');
    Route::post('/save-proforma-details2ndAppl', [HomeController::class, 'store2ndAppl'])->name('save-proforma-details2ndAppl');


    //Update Proforma Form-1
    Route::post('/Proforma_ApplicantDetails/{id}', [HomeController::class, 'viewFormUpdate'])->name('Proforma_ApplicantDetails');
    Route::get('/Proforma_ApplicantDetails/{id}', [HomeController::class, 'viewFormUpdate'])->name('Proforma_ApplicantDetails');


    //View Proforma Form-1
    Route::get('/viewPersonalDetailsFrom/{id}', [HomeController::class, 'viewForm'])->name('viewPersonalDetailsFrom');

    //for Verify
    Route::get('/verifyPersonalDetailsFrom/{id}', [HomeController::class, 'verifyForm'])->name('verifyPersonalDetailsFrom');

    //for Revert
    Route::post('/revertPersonalDetailsFrom/{id}', [HomeController::class, 'revertForm'])->name('revertPersonalDetailsFrom');
    Route::get('/revertPersonalDetailsFrom/{id}', [HomeController::class, 'revertForm'])->name('revertPersonalDetailsFrom');

    Route::post('/revertPersonalDetailsFrom1/{id}', [HomeController::class, 'revertForm'])->name('revertPersonalDetailsFrom1');
    Route::get('/revertPersonalDetailsFrom1/{id}', [HomeController::class, 'revertForm'])->name('revertPersonalDetailsFrom1');

    Route::post('/revertDetailsFrom/{id}', [HomeController::class, 'revertFormVerified'])->name('revertDetailsFrom');
    Route::get('/revertDetailsFrom/{id}', [HomeController::class, 'revertFormVerified'])->name('revertDetailsFrom');

    Route::post('/revertDetailsFrom1/{id}', [HomeController::class, 'revertFormVerified'])->name('revertDetailsFrom1');
    Route::get('/revertDetailsFrom1/{id}', [HomeController::class, 'revertFormVerified'])->name('revertDetailsFrom1');

    //below is forward by hod assist to hod
    Route::post('/forwardDetailsFrom/{id}', [HomeController::class, 'forwardHOD'])->name('forwardDetailsFrom');

    // below is revert by hod to hod assist
    Route::post('/revertDetailsFromHOD/{id}', [HomeController::class, 'revertFromHOD'])->name('revertDetailsFromHOD');
    Route::get('/revertDetailsFromHOD/{id}', [HomeController::class, 'revertFromHOD'])->name('revertDetailsFromHOD');

    Route::post('/revertDetailsFromHOD1/{id}', [HomeController::class, 'revertFromHOD'])->name('revertDetailsFromHOD1');
    Route::get('/revertDetailsFromHOD1/{id}', [HomeController::class, 'revertFromHOD'])->name('revertDetailsFromHOD1');
    // below is revert by AD Assistant to hod
    Route::post('/revertDetailsFromADAsistant/{id}', [HomeController::class, 'revertFromADAssistant'])->name('revertDetailsFromADAsistant');
    Route::get('/revertDetailsFromADAsistant/{id}', [HomeController::class, 'revertFromADAssistant'])->name('revertDetailsFromADAsistant');

    Route::post('/revertDetailsFromADAsistant1/{id}', [HomeController::class, 'revertFromADAssistant1'])->name('revertDetailsFromADAsistant1');
    Route::get('/revertDetailsFromADAsistant1/{id}', [HomeController::class, 'revertFromADAssistant1'])->name('revertDetailsFromADAsistant1');

    // below is revert by AD Nodal to AD Assistant
    Route::post('/revertDetailsFromADNodal/{id}', [HomeController::class, 'revertDetailsFromADNodal'])->name('revertDetailsFromADNodal');
    Route::get('/revertDetailsFromADNodal/{id}', [HomeController::class, 'revertDetailsFromADNodal'])->name('revertDetailsFromADNodal');
    // below is revert by AD Nodal to AD Assistant
    Route::post('/revertDetailsFromADNodal1/{id}', [HomeController::class, 'revertDetailsFromADNodal1'])->name('revertDetailsFromADNodal1');
    Route::get('/revertDetailsFromADNodal1/{id}', [HomeController::class, 'revertDetailsFromADNodal1'])->name('revertDetailsFromADNodal1');

    // below is revert by DP Assist to AD Nodal
    Route::post('/revertDetailsFromDPAssist/{id}', [HomeController::class, 'revertDetailsFromDPAssist'])->name('revertDetailsFromDPAssist');
    Route::get('/revertDetailsFromDPAssist/{id}', [HomeController::class, 'revertDetailsFromDPAssist'])->name('revertDetailsFromDPAssist');

    Route::post('/revertDetailsFromDPNodal/{id}', [HomeController::class, 'revertDetailsFromDPNodal'])->name('revertDetailsFromDPNodal');
    Route::get('/revertDetailsFromDPNodal/{id}', [HomeController::class, 'revertDetailsFromDPNodal'])->name('revertDetailsFromDPNodal');


    // below is revert by DP nodal to dp assist
    Route::post('/revertDetailsFromDPNodal/{id}', [HomeController::class, 'revertDetailsFromDPNodal'])->name('revertDetailsFromDPNodal');
    Route::get('/revertDetailsFromDPNodal/{id}', [HomeController::class, 'revertDetailsFromDPNodal'])->name('revertDetailsFromDPNodal');

    Route::post('/revertDetailsFromDPNodal1/{id}', [HomeController::class, 'revertDetailsFromDPNodal1'])->name('revertDetailsFromDPNodal1');
    Route::get('/revertDetailsFromDPNodal1/{id}', [HomeController::class, 'revertDetailsFromDPNodal1'])->name('revertDetailsFromDPNodal1');

    // below is forward from hod to AD
    Route::post('/forwardDetailsFromHOD/{id}', [HomeController::class, 'forwardByHODToADAssist'])->name('forwardDetailsFromHOD');
    Route::get('/forwardDetailsFromHOD/{id}', [HomeController::class, 'forwardByHODToADAssist'])->name('forwardDetailsFromHOD');
    // below is forward from AD Assistant to AD Nodal Officer
    Route::post('/forwardDetailsFromADToADNodal/{id}', [HomeController::class, 'forwardByADAssistToADNodal'])->name('forwardDetailsFromADToADNodal');
    Route::get('/forwardDetailsFromADToADNodal/{id}', [HomeController::class, 'forwardByADAssistToADNodal'])->name('forwardDetailsFromADToADNodal');

    // below is forward from AD Nodal Officer to DP Assist
    Route::post('/forwardDetailsFromADNodalToDPAssist/{id}', [HomeController::class, 'forwardDetailsFromADNodalToDPAssist'])->name('forwardDetailsFromADNodalToDPAssist');
    Route::get('/forwardDetailsFromADNodalToDPAssist/{id}', [HomeController::class, 'forwardDetailsFromADNodalToDPAssist'])->name('forwardDetailsFromADNodalToDPAssist');

    // below is forward from DP Assist to DP Nodal
    Route::post('/forwardDetailsFromDPAssistToDPNodal/{id}', [HomeController::class, 'forwardDetailsFromDPAssistToDPNodal'])->name('forwardDetailsFromDPAssistToDPNodal');
    Route::get('/forwardDetailsFromDPAssistToDPNodal/{id}', [HomeController::class, 'forwardDetailsFromDPAssistToDPNodal'])->name('forwardDetailsFromDPAssistToDPNodal');

    // // below is forward from DP Assist to DP Nodal

    ///////////////FAMILY///////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::post('/forward-selected-einsDPAssistToDPNodal', [HomeController::class, 'forwardSelectedEINsDPAssistToDPNodal'])->name('forwardSelectedEINsDPAssistToDPNodal');

    Route::post('/selected-ein', [FillUOController::class, 'fill_uo_selected'])->name('check_fill_uo');

    Route::get('/selected-ein', [FillUOController::class, 'fill_uo_selected'])->name('check_fill_uo');

    // Family Details
    Route::get('/enter-family-details', [FamilyMembersController::class, 'index1'])->name('enter-family-details');
    Route::post('/save-family-details', [FamilyMembersController::class, 'store'])->name('save-family-details');
    Route::get('/delete-family-member/{id}', [FamilyMembersController::class, 'destroy'])->name('delete-family-member');


    //Update Family Details
    Route::get('/view-family-details-dihas', [FamilyMembersController::class, 'indexupdate'])->name('view-family-details-dihas');
    Route::post('/save-family-details-update', [FamilyMembersController::class, 'storeLeft'])->name('save-family-details-update');
    Route::get('/delete-family-member-update/{id}', [FamilyMembersController::class, 'destroyUpdate'])->name('delete-family-member-update');


    // Family Details backlog
    Route::get('/enter-family-details-backlog', [FamilyMembersController::class, 'indexBacklog'])->name('enter-family-details-backlog');
    Route::post('/save-family-details-backlog', [FamilyMembersController::class, 'store1'])->name('save-family-details-backlog');
    Route::get('/delete-family-member-backlog/{id}', [FamilyMembersController::class, 'destroy1'])->name('delete-family-member-backlog');

    // Family Details
    Route::get('/enter-family-details2ndAppl', [FamilyMembersController::class, 'index2ndAppl'])->name('enter-family-details2ndAppl');
    Route::post('/save-family-details2ndAppl', [FamilyMembersController::class, 'store2ndAppl'])->name('save-family-details2ndAppl');
    Route::get('/delete-family-member2ndAppl/{id}', [FamilyMembersController::class, 'destroy2ndAppl'])->name('delete-family-member2ndAppl');

    // Family Details view
    Route::get('/view-family-details', [FamilyMembersController::class, 'index'])->name('view-family-details');

    /////////////////////////END OF FAMILY////////////////////////////////////////////////////////////////////////////////////////////

    Route::post('/fill_uo_save_checked/{id}', [FillUOController::class, 'fill_uo_save_checked'])->name('fill_uo_save_checked');
    Route::get('/fill_uo_save_checked/{id}', [FillUOController::class, 'fill_uo_save_checked'])->name('fill_uo_save_checked');


    // others from details
    Route::get('/other_form_details', [OthersFormDetailsController::class, 'index'])->name('other_form_details');
    //Route::get('/other_form_details_submit', [OthersFormDetailsController::class, 'index'])->name('other_form_details');

    Route::post('/save-other-details', [OthersFormDetailsController::class, 'store'])->name('save-other-details');

    //below anand for fresh form submit
    Route::get('/other_form_details_submit', [OthersFormDetailsController::class, 'index1'])->name('other_form_details_submit');
    Route::get('/submit-forms', [OthersFormDetailsController::class, 'submitForms1'])->name('submit-forms');

    //submission last part for backlog
    Route::get('/other_form_details_submit_backlog', [OthersFormDetailsController::class, 'index2'])->name('other_form_details_submit_backlog');
    Route::get('/submit-forms-backlog', [OthersFormDetailsController::class, 'submitForms2'])->name('submit-forms-backlog');

    //second appl
    Route::get('/other_form_details_submit2ndAppl', [OthersFormDetailsController::class, 'index2ndAppl'])->name('other_form_details_submit2ndAppl');
    Route::get('/submit-forms2ndAppl', [OthersFormDetailsController::class, 'submitForms2ndAppl'])->name('submit-forms2ndAppl');

    //below anand for update form submit
    Route::get('/other_form_details_dihas', [OthersFormDetailsController::class, 'indexUpdate'])->name('other_form_details_dihas');
    Route::get('/submit-forms-update', [OthersFormDetailsController::class, 'submitFormsUpdate'])->name('submit-forms-update');
    ///////////////////////////////////////////////////////PDF///////////////////////////////
    Route::get('/downloadDetails', [OthersFormDetailsController::class, 'downloadDetailsPdf'])->name('downloadDetails');

    /////////////////////////////////////UO APPROVE LIST////////////////////////////////////////
    Route::post('/approvedListFromDP/{id}', [HomeController::class, 'approvedListFromDP'])->name('approvedListFromDP');
    Route::get('/approvedListFromDP/{id}', [HomeController::class, 'approvedListFromDP'])->name('approvedListFromDP');

    ////////////////////////////
    Route::post('/revertListFromDP/{id}', [HomeController::class, 'revertListFromDP'])->name('revertListFromDP');
    Route::get('/revertListFromDP/{id}', [HomeController::class, 'revertListFromDP'])->name('revertListFromDP');


    //FOR HOD ASSISTANT
    Route::put('/updateajaxvacancy/{id}', [VacancyController::class, 'update']);
    Route::post('/updateVacancy', [VacancyController::class, 'updateVacan']);
    Route::put('/updateVacancy/{id}', [VacancyController::class, 'updateVacan']);

    Route::get('/vacancy', [VacancyController::class, 'index'])->name('vacancy.index');
    Route::put('/vacancy/{id}', [VacancyController::class, 'update'])->name('vacancy.update');

    Route::get('/vacancySearch', [VacancyController::class, 'vacancySearch'])->name('vacancySearch');
    
    Route::post('/vacancySearch', [VacancyController::class, 'vacancySearch'])->name('vacancySearch');

    Route::get('/vacancystatusSearch', [VacancyController::class, 'vacancystatusSearch'])->name('vacancystatusSearch');
    Route::post('/vacancystatusSearch', [VacancyController::class, 'vacancystatusSearch'])->name('vacancystatusSearch');

    Route::get('/vacancystatus', [VacancyController::class, 'vacancystatus'])->name('vacancystatus');

    Route::get('/download-pdf', [HomeController::class, 'downloadPdfDPVacancy'])->name('download.pdf');

    Route::post('/vacancy/update', [VacancyController::class, 'update'])->name('vacancy.update.ajax');

    ////////////////////////////////////////////////////////////
    // Route::get('/vacancy_list_view', [VacancyUpdateController::class, 'vacancy_list_view'])->name('vacancy_list_view');
    // Route::post('/vacancy_list_view', [VacancyUpdateController::class, 'vacancy_list_view'])->name('vacancy_list_view');

    Route::get('/vacancy_list_dpview', [VacancyUpdateController::class, 'vacancy_list_dpview'])->name('vacancy_list_dpview');
    Route::post('/vacancy_list_dpview', [VacancyUpdateController::class, 'vacancy_list_dpview'])->name('vacancy_list_dpview');

    //Route::get('/vacancy_list_dpview', [VacancyUpdateController::class, 'vacancy_list_dpview'])->name('vacancy_list_dpview');
    Route::post('/vacancy_list_dpviewSearch', [VacancyUpdateController::class, 'vacancy_list_dpviewSearch'])->name('vacancy_list_dpviewSearch');
    Route::get('/vacancy_list_dpviewSearch', [VacancyUpdateController::class, 'vacancy_list_dpviewSearch'])->name('vacancy_list_dpviewSearch');

    Route::post('submit-card1-form', [SubmitController::class, 'submitCard1Form'])->name('submitCard1Form');
    // Route::post('submit-card2-form',  [SubmitController::class, 'submitCard2Form'])->name('submitCard2Form');
    Route::get('submit-card1-form', [SubmitController::class, 'submitCard1Form'])->name('submitCard1Form');
    // Route::get('submit-card2-form',  [SubmitController::class, 'submitCard2Form'])->name('submitCard2Form');


    // Route::get('/fill_uo/{id}', [FillUOController::class, 'FillUO'])->name('fill_uo');
    // Route::post('/fill_uo/{id}', [FillUOController::class, 'FillUO'])->name('fill_uo');

    // Route::post('/fill_uo_save/{id}', [FillUOController::class, 'save'])->name('fill_uo_save');
    // Route::get('/fill_uo_save/{id}', [FillUOController::class, 'save'])->name('fill_uo_save');


    Route::post('/uo_submit/{id}', [HomeController::class, 'UOform'])->name('uo_submit');


    ///////////////////////////////////////////////////




    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('/viewFormWord/{id}', [HomeController::class, 'viewFormWord'])->name('viewFormWord');
    Route::get('/viewFormWord/{id}', [HomeController::class, 'viewFormWord'])->name('viewFormWord');

    Route::get('/generate-pdf/{id}', [GenerateUOController::class, 'generatePDF'])->name('generate-pdf');

    Route::get('/viewOrder/{id}', [GenerateUOController::class, 'generatePDFORDER'])->name('viewOrder');
    // Route::post('/viewFormWordGroup', [HomeController::class, 'viewFormWordGroup'])->name('viewFormWordGroup');
    // Route::get('/viewFormWordGroup', [HomeController::class, 'viewFormWordGroup'])->name('viewFormWordGroup');

    Route::post('/viewApproveGroup', [GenerateUOController::class, 'pdfselected'])->name('viewApproveGroup');
    Route::get('/viewApproveGroup', [GenerateUOController::class, 'pdfselected'])->name('viewApproveGroup');


    Route::post('/viewOrderGroup', [GenerateUOController::class, 'pdfselectedORDER'])->name('viewOrderGroup');
    Route::get('/viewOrderGroup', [GenerateUOController::class, 'pdfselectedORDER'])->name('viewOrderGroup');
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Route::post('/viewUOForm/{id}', [HomeController::class, 'viewUOForm'])->name('viewUOForm');
    // Route::get('/viewUOForm/{id}', [HomeController::class, 'viewUOForm'])->name('viewUOForm');


});


// form data edit for descriptive role route
Route::post('/save-form-info', [DescriptiveRoleEditController::class, 'store'])->name('save-form-info');
Route::post('/save-addressDetails-info', [DescriptiveRoleEditController::class, 'saveAddressDetails'])->name('save-addressDetails-info');


/////////////////////////////////////////////////PDF//////////////////////
Route::group(['prefix' => 'notification'], function () {
    Route::controller(NotificationController::class)->group(function () {
        // Route::get('new', 'newNotification')->name('notification.new')->middleware(['auth','role:999']);
        Route::get('new', 'newNotification')->name('notification.new');
        Route::get('edit', 'editNotification')->name('notification.edit');
        Route::post('save', 'saveNotification')->name('notification.save');
        Route::get('delete/{id}', 'deleteNotification')->name('notification.delete');
        // Route::get('edit', 'editNotification')->name('notification.edit')->middleware(['auth','role:999']);
        // Route::post('save', 'saveNotification')->name('notification.save')->middleware(['auth','role:999']);
        // Route::get('delete/{id}', 'deleteNotification')->name('notification.delete')->middleware(['auth','role:999']);
        Route::get('getdoc/{id}', 'getdocNotification')->name('notification.getdoc');
    });
});

Route::group(['prefix' => 'screening'], function () {
    Route::controller(ScreeningController::class)->group(function () {
        Route::get('new', 'newNotification')->name('screening.new');
        Route::get('edit', 'editNotification')->name('screening.edit');
        Route::post('save', 'saveNotification')->name('screening.save');
        Route::get('delete/{id}', 'deleteNotification')->name('screening.delete');
        // Route::get('edit', 'editNotification')->name('notification.edit')->middleware(['auth','role:999']);
        // Route::post('save', 'saveNotification')->name('notification.save')->middleware(['auth','role:999']);
        // Route::get('delete/{id}', 'deleteNotification')->name('notification.delete')->middleware(['auth','role:999']);
        Route::get('getdoc/{id}', 'getdocNotification')->name('screening.getdoc');
        Route::get('view', 'viewScreeningReport')->name('screening.view');
    });
});

//citizen 
Route::group(['prefix' => 'citizen'], function () {
    Route::controller(LoginRegisterController::class)->group(function () {
        Route::post('register', 'citizenregister')->name('citizen.register');
        Route::get('register', 'citizenregister')->name('citizen.register');
        Route::post('preRegistration', 'citizenPreRegistration')->name('citizen.preRegistration');
        Route::get('preRegistration', 'citizenPreRegistration')->name('citizen.preRegistration');
        Route::post('saveRegistration', 'citizenSaveRegistration')->name('citizen.saveRegistration');
        Route::post('resendSmsOTP', 'smsRegistrationOTP')->name('citizen.smsRegistrationOTP');
        Route::post('resendEmailOTP', 'emailRegistrationOTP')->name('citizen.emailRegistrationOTP');
    });
});
//state
Route::group(['prefix' => 'state'], function () {
    Route::controller(AddressController::class)->group(function () {
        // Route::post('getState', 'getStateOption')->name('state.getOption');
        Route::post('getDistrict', 'getDistrictOption')->name('district.getOption');
        // Route::get('getState', 'getStateOption')->name('state.getOption');
        // Route::get('getDistrict', 'getDistrictOption')->name('district.getOption');
    });
});

Route::group(['prefix' => 'district1'], function () {
    Route::controller(AddressController::class)->group(function () {
        // Route::post('getState1', 'getStateOption')->name('state.getOption');
        Route::post('getDistrict1', 'getDistrictOption1')->name('district1.getOption');
        Route::post('getSubDivision1', 'getSubDivisionOption1')->name('SubDivision1.getOption');
        // Route::get('getDistrict1', 'getDistrictOption1')->name('district1.getOption');
        // Route::get('getSubDivision1', 'getSubDivisionOption1')->name('SubDivision1.getOption');
    });
});

//Proforma Delete and SAVE
Route::delete('/delete/{ein}', [HomeController::class, 'deleteRecord']);

//for citizen
Route::delete('/deleteself/{ein}', [HomeController::class, 'deleteRecordself']);

Route::delete('/delete2ndAppl/{ein}', [HomeController::class, 'delete2ndAppl']);


//Route::post('/save_proforma',[HomeController::class, 'store'])->name('save_proforma');

//One time run from Cron Jobs
Route::get('/cmis_vacancy_yearly', [CmisVacancyController::class, 'index'])->name('cmis_vacancy_yearly');

Route::get('/downloadpdfvacancy', [VacancyController::class, 'downloadpdfvacancy'])->name('downloadpdfvacancy');
//Route::get('/downloadpdfvacancydp', [VacancyUpdateController::class, 'downloadpdfvacancydp'])->name('downloadpdfvacancydp');

Route::get('/successstories', 'App\Http\Controllers\SuccessStoriesController@index');
Route::get('/successstory/create', 'App\Http\Controllers\SuccessStoriesController@create');
Route::post('/successstory/create', 'App\Http\Controllers\SuccessStoriesController@store');
Route::get('/successstory/{id}/edit', 'App\Http\Controllers\SuccessStoriesController@edit');
Route::put('/successstory/{id}/edit', 'App\Http\Controllers\SuccessStoriesController@update');
Route::delete('/successstory/delete/{id}', 'App\Http\Controllers\SuccessStoriesController@destroy');
Route::get('/images/{filename}', [SuccessStoriesController::class, 'show'])->name('image.show');

Route::get('/dihas_overview', [LandingPageController::class, 'dihas_overview'])->name('dihas_overview');
Route::get('/sitemap', [LandingPageController::class, 'sitemap'])->name('sitemap');
Route::get('/department_login', [LandingPageController::class, 'department_login'])->name('department_login');
Route::get('/contact_us', [LandingPageController::class, 'contact_us'])->name('contact_us');

Route::get('/uploadimages', 'App\Http\Controllers\UploadImageController@index');
Route::get('/uploadimage/create', 'App\Http\Controllers\UploadImageController@create');
Route::post('/uploadimage/create', 'App\Http\Controllers\UploadImageController@store');
Route::get('/uploadimage/{id}/edit', 'App\Http\Controllers\UploadImageController@edit');
Route::put('/uploadimage/{id}/edit', 'App\Http\Controllers\UploadImageController@update');
Route::delete('/uploadimage/delete/{id}', 'App\Http\Controllers\UploadImageController@destroy');


//new change on 17 may 2024
Route::post('/transferFromDPNodal/{id}', [HomeController::class, 'transferFromDPNodal'])->name('transferFromDPNodal');
    Route::get('/transferFromDPNodal/{id}', [HomeController::class, 'transferFromDPNodal'])->name('transferFromDPNodal');
	
	Route::get('/view-file/{filename}',  [HomeController::class, 'viewFileForwardByHODAssistant'])->name('viewFileForwardByHODAssistant');
	