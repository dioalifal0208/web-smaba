<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Website Resmi SMA Negeri 1 Babat
|--------------------------------------------------------------------------
| Route publik mengikuti ARCHITECTURE.md §7.
| Controller akan ditambahkan di Phase 3 saat modul konten dibangun.
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/berita', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/pengumuman/{slug}', [AnnouncementController::class, 'show'])->name('announcements.show');
