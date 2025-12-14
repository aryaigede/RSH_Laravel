<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ResepsionisDashboardController;
use App\Http\Controllers\DokterDashboardController;
use App\Http\Controllers\PerawatDashboardController;
use App\Http\Controllers\PemilikDashboardController;

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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin dashboard 
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'home'])->name('admin.dashboard');
});

// Data dashboard guarded by per-model access
Route::middleware(['auth', 'modelAccess'])->group(function () {
    Route::get('/admin/dashboard/data', [DashboardController::class, 'index'])->name('admin.dashboard.data');
    Route::post('/admin/dashboard/store', [DashboardController::class, 'store'])->name('admin.dashboard.store');
    Route::put('/admin/dashboard/{id}', [DashboardController::class, 'update'])->name('admin.dashboard.update');
    Route::delete('/admin/dashboard/{id}', [DashboardController::class, 'destroy'])->name('admin.dashboard.destroy');
});

// Resepsionis
Route::middleware(['auth', 'isResepsionis'])->group(function () {
    Route::get('/resepsionis/dashboard', [ResepsionisDashboardController::class, 'index'])->name('resepsionis.dashboard');
});

// Dokter
Route::middleware(['auth', 'isDokter'])->group(function () {
    Route::get('/dokter/dashboard', [DokterDashboardController::class, 'index'])->name('dokter.dashboard');
});

// Perawat
Route::middleware(['auth', 'isPerawat'])->group(function () {
    Route::get('/perawat/dashboard', [PerawatDashboardController::class, 'index'])->name('perawat.dashboard');
});

// Pemilik
Route::middleware(['auth', 'isPemilik'])->group(function () {
    Route::get('/pemilik/dashboard', [PemilikDashboardController::class, 'index'])->name('pemilik.dashboard');
});