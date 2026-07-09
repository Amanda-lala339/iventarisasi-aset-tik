<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\AssetController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('servers', ServerController::class);
Route::resource('subdomains', SubdomainController::class);
Route::resource('assets', AssetController::class);

Route::post('/assets/import', [AssetController::class, 'import'])->name('assets.import');
Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
Route::get('assets/category/data-informasi', [AssetController::class, 'dataInformasi'])->name('assets.category.di');
Route::get('assets/category/perangkat-lunak', [AssetController::class, 'perangkatLunak'])->name('assets.category.pl');
Route::get('assets/category/perangkat-keras', [AssetController::class, 'perangkatKeras'])->name('assets.category.pk');
Route::get('assets/category/sarana-pendukung', [AssetController::class, 'saranaPendukung'])->name('assets.category.sp');
Route::get('assets/category/sdm-pihak-ketiga', [AssetController::class, 'sdmPihakKetiga'])->name('assets.category.ps');