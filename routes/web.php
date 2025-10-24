<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;

// Home/Welcome page
Route::get('/', [SiteController::class, 'home'])->name('home');

// Site routes
Route::get('/home', [SiteController::class, 'home'])->name('site.home');
Route::get('/layanan-umum', [SiteController::class, 'layananUmum'])->name('site.layanan-umum');
Route::get('/login', [SiteController::class, 'login'])->name('site.login');
Route::get('/struktur', [SiteController::class, 'struktur'])->name('site.struktur');
Route::get('/visi-misi', [SiteController::class, 'visiMisi'])->name('site.visi-misi');