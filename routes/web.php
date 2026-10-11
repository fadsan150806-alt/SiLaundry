<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    // Pembatasan percobaan login (brute force) ditangani di AuthController.
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
            ->name('dashboard.admin');

        Route::resource('users', UserController::class)
            ->except(['show']);
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])
            ->name('dashboard.kasir');
    });

    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])
            ->name('dashboard.pelanggan');
    });
});
