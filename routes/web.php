<?php

use App\Http\Controllers\Admin\SettingController;
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
    // CLIENTS ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::prefix('clients')
        ->name('clients.')
        ->group(function () {
            Route::get('/create', [ClientController::class, 'create'])->name('create');
            Route::post('/', [ClientController::class, 'store'])->name('store');
            Route::get('/trashed', [ClientController::class, 'trashed'])->name('trashed');
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/{client}', [ClientController::class, 'show'])->name('show');
            Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/{client}', [ClientController::class, 'update'])->name('update');
            Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        });

    // ─────────────────────────────────────────────────────────────
    // APPOINTMENTS ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::prefix('appointments')
        ->name('appointments.')
        ->group(function () {
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('update-status');

            // KANBAN - MUST come BEFORE the wildcard {appointment} route
            Route::get('/kanban', [AppointmentController::class, 'kanban'])->name('kanban');

            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
            Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
            Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
        });

    // ─────────────────────────────────────────────────────────────
    // SERVICE RECORDS ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::prefix('service-records')
        ->name('service-records.')
        ->group(function () {
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
    // PROFILE
    // ─────────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─────────────────────────────────────────────────────────────
    // ADMIN ONLY ROUTES
    // ─────────────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

                // IMPORTANT: These must come AFTER the main routes
                Route::get('/trashed', [UserController::class, 'trashed'])->name('trashed');
                Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
                Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
            });

        // Client restore & force delete (admin only)
        Route::prefix('clients')
            ->name('clients.')
            ->group(function () {
                Route::post('/{id}/restore', [ClientController::class, 'restore'])->name('restore');
                Route::delete('/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('force-delete');
            });

        // System Settings (admin only) - MOVED INSIDE the admin group
        Route::prefix('settings')
            ->name('admin.settings.')
            ->group(function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::put('/', [SettingController::class, 'update'])->name('update');
                Route::get('/reset', [SettingController::class, 'reset'])->name('reset');
            });
    });
});

require __DIR__ . '/auth.php';
