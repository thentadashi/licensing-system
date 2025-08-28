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
use App\Http\Controllers\Admin\ApplicationArchiveController as AdminApplicationArchiveController;
use App\Http\Controllers\Admin\ApplicationTrashController as AdminApplicationTrashController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\FormController;



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
    Route::put('/reset-password', [PasswordController::class, 'update'])->name('password.update'); // for updating password

    Route::get('/new-password', [NewPasswordController::class, 'create'])->name('password.new'); // for showing new password form
    Route::post('/new-password', [NewPasswordController::class, 'store'])->name('password.new.store'); // for storing new password

    Route::get('/application', [ApplicationController::class, 'application'])->name('application'); // for viewing the page
    Route::post('/application', [ApplicationController::class, 'store'])->name('applications.store'); // for submitting the form
    Route::get('/applications', [ApplicationController::class, 'application'])->name('applications'); // for viewing the applications
    Route::post('/applications/{application}/extra-fields', [ApplicationController::class, 'storeExtraFields'])->name('applications.storeExtraFields');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');  // for viewing the profile edit page
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // for updating the profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');      // for deleting the profile

    Route::get('/license-requirements', function () {return view('license-requirements');})->name('license-requirements'); // for viewing the license requirements

    Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('announcements'); // for viewing announcements

    Route::get('/applications/{application}/revision', [StudentApplicationController::class, 'showRevisionForm'])->name('applications.showRevisionForm'); // for showing the revision form
    Route::post('/applications/{application}', [ApplicationController::class, 'submitRevision'])->name('applications.submitRevision');
    Route::get('/applications/{application}', [StudentApplicationController::class, 'show'])->name('applications.show'); // for viewing a specific application
    Route::post('/applications/{application}/reupload', [ApplicationController::class, 'reupload'])->name('applications.reupload');

    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');




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
        Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');       
        Route::resource('students', StudentController::class)->except(['create', 'store', 'show']);
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
    });

// Super Admin & Clerk
Route::middleware(['auth', 'role:super_admin, clerk'])
    ->prefix('admin')
    ->group(function () {
        // Archive routes
        Route::get('/applications/archives', [AdminApplicationArchiveController::class, 'index'])->name('admin.applications.archives.index');
        Route::post('/applications/{application}/archive', [AdminApplicationArchiveController::class, 'store'])->name('admin.applications.archive');
        Route::delete('/applications/archives/{archive}', [AdminApplicationArchiveController::class, 'destroy'])->name('admin.applications.archives.unarchive');
        Route::post('/applications/archives/{archive}/restore', [AdminApplicationArchiveController::class, 'restore'])->name('admin.applications.archives.restore');
        // Trash routes
        Route::get('/applications/trash', [AdminApplicationTrashController::class, 'index'])->name('admin.applications.trash.index');
        Route::post('/applications/{application}/trash', [AdminApplicationTrashController::class, 'store'])->name('admin.applications.trash');
        Route::post('/applications/trash/{trash}/restore', [AdminApplicationTrashController::class, 'restore'])->name('admin.applications.trash.restore');
        Route::delete('/applications/trash/{trash}', [AdminApplicationTrashController::class, 'destroy'])->name('admin.applications.trash.destroy');

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

    // ===================== Shared Routes Students + admins ======================================

    // works for students + admins
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    });

    
