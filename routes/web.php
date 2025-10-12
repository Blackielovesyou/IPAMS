<?php

use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\JugController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlockedAccountController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\ModuleAccessController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShareableController;
use App\Models\SchoolYearModel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware('redirect.nonlogin');


Route::get('/home', function () {
    return view('welcome');
})->middleware('redirect.nonlogin');


Route::middleware(['redirect.nonlogin', 'guest'])->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/loginsubmit', [AuthController::class, 'login']);
    Route::post('/password-reset-request', [AuthController::class, 'resetPasswordRequest'])->name('password.reset.request');
    Route::get('/password-reset', [AuthController::class, 'showPasswordResetForm']);
    Route::post('/password-reset-submit', [AuthController::class, 'submitNewPassword']);
    Route::get('/syncSystemStatus', [AuthController::class, 'syncSystemStatus']);
    Route::post('/registersubmit', [AuthController::class, 'registersubmit']);
});
Route::middleware(['auth'])->group(function () {

    // Admin routes
    Route::middleware(['admin'])->group(function () {
        // View Route
        Route::get('/admin', [dashboardController::class, 'display_dashboard'])->name('admin.dashboard');
        // Settings
        Route::middleware(['check.module.access:1007'])->group(function () {
            Route::get('/admin/settings', [SettingsController::class, 'settingsPage'])->name('settings');
        });
        // User Management
        Route::middleware(['check.module.access:1000'])->group(function () {
            Route::get('/admin/add_staff_account', [UsersController::class, 'add_staff_account']);
            Route::get('/admin/add_inspector_account', [UsersController::class, 'add_inspector_account']);
            Route::get('/admin/add_applicant_account', [UsersController::class, 'add_applicant_account']);
            Route::post('/admin/submit_staff_account', [UsersController::class, 'submit_staff_account']);
            Route::post('/admin/submit_inspector_account', [UsersController::class, 'submit_inspector_account']);
            Route::post('/admin/submit_applicant_account', [UsersController::class, 'submit_applicant_account']);
            Route::view('/admin/users_management', 'admin.pages.users_management')->name('users_management');
            Route::view('/admin/users_verification', 'admin.pages.users_verification')->name('users_verification');
        });


        // Audit Trail
        Route::middleware(['check.module.access:1006'])->group(function () {
            Route::view('/admin/audit', 'admin.pages.audit')->name('audit');
        });

        // Get Data For Datatable

        Route::get('/admin/get_users', [UsersController::class, 'get_users'])->name('get_users');
        Route::get('/admin/get_audit', [AuditController::class, 'index'])->name('get_audit');

        Route::middleware(['web'])->group(function () {
            // Function routes

            Route::post('/admin/user_verification', [UsersController::class, 'verifyUser']);


            Route::post('/admin/update_app_info', [SettingsController::class, 'updateapp_info'])->name('admin.updateAppInfo');

            // Payment route for static tab
            Route::post('/admin/settings/payment', [SettingsController::class, 'savePayment'])
                ->name('admin.settings.payment.save');

            // Delete payment route
            Route::delete('/admin/settings/payment/{id}', [SettingsController::class, 'deletePayment'])
                ->name('admin.settings.payment.delete');


            Route::post('/admin/change_password', [UsersController::class, 'changePassword'])->name('admin.changepassword');
            Route::post('/admin/reset_password', [UsersController::class, 'resetPassword'])->name('admin.resetPassword');

            Route::post('/admin/blocked-account', [BlockedAccountController::class, 'add_blocked_account'])->name('add_blocked_account');
            Route::delete('/admin/activate_user/{id}', [BlockedAccountController::class, 'delete_blocked_account'])->name('delete_blocked_account');

            Route::get('/admin/user_profile/{id}', [UsersController::class, 'display_user_profile'])->name('display_module_access');
            Route::get('/admin/module_access/{id}', [ModuleAccessController::class, 'display_module_access'])->name('display_module_access');

            Route::post('/admin/add_module_access', [ModuleAccessController::class, 'add_module_access'])->name('module_access.add_module_access');
            Route::delete('/admin/module_access/{id}/{userid}', [ModuleAccessController::class, 'delete_module_access'])->name('module_access.delete_module_access');
        });
    })->middleware('redirect.nonadmin');


    Route::middleware(['visitor'])->group(function () {

        Route::get('/user/home', function () {
            return view('user.home');
        })->name('user.home');

        Route::get('/user/permit', function () {
            $type = request()->query('type'); // capture ?type=building
            return view('user.pages.permit', compact('type'));
        })->name('user.permit');

        Route::middleware(['web'])->group(function () {
            Route::post('/user/update-profile', [UsersController::class, 'updateProfile'])->name('update-profile');
            Route::get('/user/help', function () {
                return view('user.pages.help');
            })->name('user.help');
            Route::get('/user/about', function () {
                return view('user.pages.about');
            })->name('user.about');
        });
    });


    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markNotificationsAsRead']);
    Route::get('/check-access', [ModuleAccessController::class, 'checkAccess']);
    Route::get('/get_sy', [ShareableController::class, 'get_sy'])->name('get_sy');
    Route::get('/get_teacher_type', [ShareableController::class, 'get_teacher_type'])->name('get_teacher_type');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});




