<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplainantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Complainant Routes ---
Route::middleware(['auth', 'role:complainant'])->prefix('complainant')->name('complainant.')->group(function () {
    Route::get('/dashboard', [ComplainantController::class, 'dashboard'])->name('dashboard');
    Route::get('/complaints', [ComplainantController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplainantController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplainantController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [ComplainantController::class, 'show'])->name('complaints.show');
    Route::get('/feedback/create', [ComplainantController::class, 'feedbackCreate'])->name('feedback.create');
    Route::post('/feedback', [ComplainantController::class, 'feedbackStore'])->name('feedback.store');
    Route::get('/notifications', [ComplainantController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/read', [ComplainantController::class, 'markNotificationsRead'])->name('notifications.read');
});

// --- Admin Routes (admin + superadmin) ---
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/complaints', [AdminController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [AdminController::class, 'show'])->name('complaints.show');
    Route::patch('/complaints/{complaint}/status', [AdminController::class, 'updateStatus'])->name('complaints.status');
    Route::post('/complaints/{complaint}/respond', [AdminController::class, 'respond'])->name('complaints.respond');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback.index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminController::class, 'exportPdf'])->name('reports.export');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
});

// --- Super Admin Routes ---
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::resource('/users', SuperAdminController::class);
    Route::resource('/categories', CategoryController::class)->except(['show']);
    Route::patch('/users/{user}/toggle', [SuperAdminController::class, 'toggleActive'])->name('users.toggle');
});

require __DIR__.'/auth.php';
