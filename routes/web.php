<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Home routes
Route::get('/', [SiteController::class, 'home'])->name('home');

// Site routes
Route::get('/home', [SiteController::class, 'home'])->name('site.home');
Route::get('/layanan-umum', [SiteController::class, 'layananUmum'])->name('site.layanan-umum');
Route::get('/struktur', [SiteController::class, 'struktur'])->name('site.struktur');
Route::get('/visi-misi', [SiteController::class, 'visiMisi'])->name('site.visi-misi');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Dashboard routes 
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::put('/dashboard/{id}', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
});