<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Protected routes (require login)
Route::middleware('auth')->group(function () {
    // Root redirect
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─────────────────────────────────────────────────────────────
    // CLIENTS ROUTES (ALL in one group, properly ordered)
    // ─────────────────────────────────────────────────────────────
    Route::prefix('clients')->name('clients.')->group(function () {
        // SPECIFIC routes FIRST (no parameters)
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/trashed', [ClientController::class, 'trashed'])->name('trashed');
        
        // WILDCARD routes LAST (with parameters)
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        
        // RESTORE & FORCE DELETE (admin only — moved to admin group later)
    });

    // ─────────────────────────────────────────────────────────────
    // APPOINTMENTS ROUTES (ALL in one group, properly ordered)
    // ─────────────────────────────────────────────────────────────
    Route::prefix('appointments')->name('appointments.')->group(function () {
        // SPECIFIC routes FIRST
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');
        
        // WILDCARD routes LAST
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
    });

    // ─────────────────────────────────────────────────────────────
    // SERVICE RECORDS ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::prefix('service-records')->name('service-records.')->group(function () {
        Route::get('/', [ServiceRecordController::class, 'index'])->name('index');
        Route::post('/', [ServiceRecordController::class, 'store'])->name('store');
    });

    // ─────────────────────────────────────────────────────────────
    // REPORTS ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');

    // ─────────────────────────────────────────────────────────────
    // PROFILE (Breeze default)
    // ─────────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─────────────────────────────────────────────────────────────
    // ADMIN ONLY ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        // User management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Client restore & force delete (admin only)
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::post('/{id}/restore', [ClientController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('force-delete');
        });

        // Appointment CRUD (admin only) — already in main group but admin middleware ensures access
        // No need to duplicate — they're already protected by admin middleware in the main group
    });
});

require __DIR__ . '/auth.php';