<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
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

// ====================== STUDENT ROUTES ======================
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('dashboard');
    Route::post('/application', [ApplicationController::class, 'application'])->name('application');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
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
    });


