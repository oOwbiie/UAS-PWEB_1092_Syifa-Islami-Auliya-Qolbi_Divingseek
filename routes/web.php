<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Customer routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'loginSubmit']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'registerSubmit']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Customer authenticated routes
    Route::get('/reserve/{package_id}', [HomeController::class, 'reserveForm'])->name('reserve.form');
    Route::post('/reserve', [HomeController::class, 'reserveSubmit'])->name('reserve.submit');
    Route::get('/payment/{reservation_id}', [HomeController::class, 'paymentPage'])->name('payment.page');
    Route::post('/payment/upload/{reservation_id}', [HomeController::class, 'uploadBukti'])->name('payment.upload');
    Route::get('/reservation/{reservation_id}', [HomeController::class, 'reservationDetail'])->name('reservation.detail');
    Route::get('/my-reservations', [HomeController::class, 'myReservations'])->name('my.reservations');

    // Admin routes (using admin middleware)
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Admin Packages CRUD
        Route::get('/admin/packages', [AdminController::class, 'packagesIndex'])->name('admin.packages.index');
        Route::get('/admin/packages/create', [AdminController::class, 'packagesCreate'])->name('admin.packages.create');
        Route::post('/admin/packages/store', [AdminController::class, 'packagesStore'])->name('admin.packages.store');
        Route::get('/admin/packages/{id}/edit', [AdminController::class, 'packagesEdit'])->name('admin.packages.edit');
        Route::post('/admin/packages/{id}/update', [AdminController::class, 'packagesUpdate'])->name('admin.packages.update');
        Route::post('/admin/packages/{id}/delete', [AdminController::class, 'packagesDestroy'])->name('admin.packages.delete');

        // Admin Payment Verification
        Route::get('/admin/verifikasi', [AdminController::class, 'verifikasiIndex'])->name('admin.verifikasi.index');
        Route::post('/admin/verifikasi/{id}/approve', [AdminController::class, 'verifikasiApprove'])->name('admin.verifikasi.approve');
        Route::post('/admin/verifikasi/{id}/reject', [AdminController::class, 'verifikasiReject'])->name('admin.verifikasi.reject');

        // Admin Reservations
        Route::post('/admin/reservations/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.reservations.status');
        Route::post('/admin/reservations/{id}/delete', [AdminController::class, 'reservationsDestroy'])->name('admin.reservations.delete');
        
        // Admin Contact Information
        Route::get('/admin/contact', [AdminController::class, 'contactEdit'])->name('admin.contact.edit');
        Route::post('/admin/contact/update', [AdminController::class, 'contactUpdate'])->name('admin.contact.update');
    });
});
