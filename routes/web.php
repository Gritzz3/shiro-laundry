<?php

use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page with tracking form
Route::get('/', [TrackingController::class, 'index'])->name('home');

// Tracking API endpoint (supports GET & POST)
Route::match(['get', 'post'], '/tracking', [TrackingController::class, 'search'])->name('tracking.search');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Order management
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('orders.update-status');

    // Super Admin only routes
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });
});
