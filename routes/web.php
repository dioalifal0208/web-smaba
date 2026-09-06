<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Website Resmi SMA Negeri 1 Babat
|--------------------------------------------------------------------------
| Route publik mengikuti ARCHITECTURE.md §7.
| Controller akan ditambahkan di Phase 3 saat modul konten dibangun.
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('pages.home'))->name('home');
