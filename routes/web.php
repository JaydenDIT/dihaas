<?php

//use App\Http\Controllers\Duties\VerificationController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\SmsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Misc\DistrictController;
use App\Http\Controllers\Misc\SubDivisionController;
use App\Http\Controllers\TaskApplicationController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Route::get('/phpinfo', function () {
//     phpinfo();
// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('home')->middleware('auth');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    echo 'Application cache has been cleared';
});

/* Route::group(['prefix' => 'duty', 'as' => 'duty.personal.'], function () {
    Route::get('/viewPersonalDetailsFrom/{id}', [VerificationController::class, 'viewDetail'])->name('detail');
}); */

Route::group(['prefix' => 'tasks', 'as' => 'tasks.performa.'], function () {
    // Optional route to view applications for a specific task
    Route::get('/all/performa', [TaskApplicationController::class, 'allProcess'])->name('all');
    Route::get('/{tasks_id}/performa', [TaskApplicationController::class, 'index'])->name('index');
    Route::post('/{tasks_id}/performa/ajaxlist', [TaskApplicationController::class, 'ajaxlist'])->name('ajaxlist');
});

//misc routes
Route::group(['prefix' => 'misc', 'as' => 'misc.option.'], function () {
    Route::get('/{id}/district', [DistrictController::class, 'loadByState'])->name('district');
    Route::get('/{id}/subdivision', [SubDivisionController::class, 'loadByDistrict'])->name('subdivision');
});

Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
    Route::get('/forgot', function () {
        return "Under Development";
    })->name('forgot');
});
Route::group(['prefix' => 'citizen', 'as' => 'citizen.'], function () {
    Route::get('/register', function () {
        return "Under development";
    })->name('register');
});

/*** Route for sms ****/
Route::post('smsLoginCitizenOTP', [SmsController::class, 'smsLoginCitizenOTP'])->name('smsLoginCitizenOTP');
Route::get('smsLoginCitizenOTP', [SmsController::class, 'smsLoginCitizenOTP'])->name('smsLoginCitizenOTP');
Route::post('smsLoginCitizenOTPResend', [SmsController::class, 'smsLoginCitizenOTPResend'])->name('smsLoginCitizenOTPResend');

//verify otp
Route::post('smsLoginOTP', [SmsController::class, 'smsLoginOTP'])->name('smsLoginOTP');                    //To verify username and mobile//verify resend otp
Route::post('/smsLoginOTPResend', [SmsController::class, 'smsLoginOTPResend'])->name('smsLoginOTPResend'); //To verify username and mobile

//state
Route::group(['prefix' => 'state'], function () {
    Route::controller(AddressController::class)->group(function () {
        Route::post('getDistrict', 'getDistrictOption')->name('district.getOption');
    });
});

Route::get('/dihas_overview', [LandingPageController::class, 'dihas_overview'])->name('dihas_overview');
Route::get('/sitemap', [LandingPageController::class, 'sitemap'])->name('sitemap');
Route::get('/contact_us', [LandingPageController::class, 'contact_us'])->name('contact_us');

require __DIR__ . '/auth.php';
