<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - BantayResolve
|--------------------------------------------------------------------------
*/

// Root URL should serve the public portal with live report statistics.
Route::get('/', function () {
    $totalReports = Report::query()->count();
    $resolvedReports = Report::query()->where('status', 'Resolved')->count();

    return view('welcome', compact('totalReports', 'resolvedReports'));
});

// ------------------- AUTHENTICATION ROUTES -------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------- CITIZEN ROUTES -------------------
Route::prefix('citizen')->middleware(['auth', 'role:citizen'])->group(function () {
    Route::get('/dashboard', [CitizenController::class, 'dashboard'])->name('citizen.dashboard');

    // Standard Resource & Named Route Aliases
    Route::get('/reports', [CitizenController::class, 'myReports'])->name('citizen.reports.index');
    Route::get('/my-reports', [CitizenController::class, 'myReports'])->name('citizen.my_reports');

    Route::get('/reports/create', [CitizenController::class, 'createReport'])->name('citizen.reports.create');
    Route::get('/create-report', [CitizenController::class, 'createReport'])->name('citizen.create_report');

    Route::post('/reports', [CitizenController::class, 'storeReport'])->name('citizen.reports.store');
    Route::post('/store-report', [CitizenController::class, 'storeReport'])->name('citizen.store_report');

    Route::delete('/reports/{id}', [CitizenController::class, 'destroy'])->name('citizen.reports.destroy');

    Route::get('/announcements', [CitizenController::class, 'announcements'])->name('citizen.announcements');
});

// ------------------- ADMIN ROUTES -------------------
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Pending Reports Queue
    Route::get('/pending', [AdminController::class, 'pendingReports'])->name('admin.pending-reports');
    Route::get('/pending-reports', [AdminController::class, 'pendingReports'])->name('admin.reports.pending');

    // Announcements
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('admin.store_announcement');
    Route::post('/announcements/store', [AdminController::class, 'storeAnnouncement'])->name('admin.announcements.store');

    // Manage Users
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::match(['post', 'put'], '/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Activity Logs
    Route::get('/logs', [AdminController::class, 'activityLogs'])->name('admin.logs');
    Route::delete('/logs/{id}', [AdminController::class, 'deleteActivityLog'])->name('admin.logs.delete');

    // Report Actions (Supports both POST & PUT to avoid 405 Method Not Allowed errors)
    Route::match(['post', 'put'], '/reports/{id}', [AdminController::class, 'updateReport'])->name('admin.reports.update');
    Route::post('/reports/{id}/update', [AdminController::class, 'updateReport'])->name('admin.reports.update.post');
    Route::delete('/reports/{id}', [AdminController::class, 'deleteReport'])->name('admin.reports.delete');
});

// ------------------- STAFF ROUTES -------------------
Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/reports', [StaffController::class, 'reports'])->name('staff.reports');
    Route::put('/reports/{report}', [StaffController::class, 'updateReport'])->name('staff.reports.update');
    Route::get('/announcements', [StaffController::class, 'announcements'])->name('staff.announcements');
    Route::post('/announcements', [StaffController::class, 'storeAnnouncement'])->name('staff.announcements.store');
});

// ------------------- USER MANAGEMENT RESOURCE -------------------
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});
