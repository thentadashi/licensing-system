<?php 

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ApplicationController as StudentApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// ====================== STUDENT LOGIN & REGISTRATION (GUEST ONLY) ======================
Route::middleware('guest')->group(function () {
    require __DIR__.'/auth.php';
});

// ====================== ADMIN LOGIN (GUEST ONLY) ======================
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// ====================== ADMIN LOGOUT ======================
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ====================== EMAIL VERIFICATION ======================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');        

// ====================== STUDENT ROUTES (EMAIL VERIFIED ONLY) ======================
Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'dashboard'])->name('dashboard'); // for viewing the dashboard
    Route::get('/application', [ApplicationController::class, 'application'])->name('application'); // for viewing the page
    Route::post('/application', [ApplicationController::class, 'store'])->name('applications.store'); // for submitting the form
    Route::get('/applications', [ApplicationController::class, 'application'])->name('applications'); // for viewing the applications
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');  // for viewing the profile edit page
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // for updating the profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');      // for deleting the profile
    Route::put('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');    // for resetting the password
    Route::get('/reset-password', [NewPasswordController::class, 'create'])->name('password.reset'); // for viewing the reset password page
    Route::get('/downloadable-forms', function () {return view('downloadable-forms');})->name('downloadable-forms'); // for viewing the downloadable forms
    Route::get('/license-requirements', function () {return view('license-requirements');})->name('license-requirements'); // for viewing the license requirements
    Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('announcements'); // for viewing announcements
    Route::get('/applications/{application}/revision', [StudentApplicationController::class, 'showRevisionForm'])->name('applications.showRevisionForm'); // for showing the revision form
    Route::post('/applications/{application}/revision', [StudentApplicationController::class, 'submitRevision'])->name('applications.submitRevision'); // for submitting the revision
    Route::get('/applications/{application}', [StudentApplicationController::class, 'show'])->name('applications.show'); // for viewing a specific application
    Route::post('/applications/{application}/reupload', [ApplicationController::class, 'reupload'])->name('applications.reupload');
});

// ====================== ADMIN ROUTES ======================
// Super Admin only
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/settings', function () {
            return "Super Admin Settings Page";
        })->name('admin.settings');

        // Student management routes
        Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');       
        Route::resource('students', StudentController::class)->except(['create', 'store', 'show']);
        Route::get('admin/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::delete('admin/students/{student}', [AdminStudentController::class, 'destroy'])->name('admin.students.destroy');
    });

// Super Admin & Clerk
Route::middleware(['auth', 'role:super_admin,clerk'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/applications', [AdminApplicationController::class, 'index'])->name('admin.applications.index');   
        Route::patch('/applications/{application}', [AdminApplicationController::class, 'update'])->name('admin.applications.update');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

          // Fixed announcement routes
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('admin.announcements.show');
        Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleVisibility'])->name('admin.announcements.toggle');
        Route::post('/applications/{application}/revision', [AdminApplicationController::class, 'requestRevision'])->name('admin.applications.requestRevision');
        Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('admin.applications.show');
    });
