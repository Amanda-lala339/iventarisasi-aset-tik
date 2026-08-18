<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetDocumentController;
use App\Http\Controllers\MasterDataController;


require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('servers', ServerController::class);
    Route::resource('subdomains', SubdomainController::class);

    Route::get('assets/category/data-informasi', [AssetController::class, 'dataInformasi'])->name('assets.category.di');
    Route::get('assets/category/perangkat-lunak', [AssetController::class, 'perangkatLunak'])->name('assets.category.pl');
    Route::get('assets/category/perangkat-keras', [AssetController::class, 'perangkatKeras'])->name('assets.category.pk');
    Route::get('assets/category/sarana-pendukung', [AssetController::class, 'saranaPendukung'])->name('assets.category.sp');
    Route::get('assets/category/sdm-pihak-ketiga', [AssetController::class, 'sdmPihakKetiga'])->name('assets.category.ps');

    Route::post('assets/import', [AssetController::class, 'import'])->name('assets.import');

    Route::resource('assets', AssetController::class);

    Route::get('assets/category/{category}', [AssetController::class, 'category'])->name('assets.category');

    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', [MasterDataController::class, 'dashboard'])->name('dashboard');
        Route::get('/{type}', [MasterDataController::class, 'index'])->name('index');
        Route::get('/{type}/create', [MasterDataController::class, 'create'])->name('create');
        Route::post('/{type}', [MasterDataController::class, 'store'])->name('store');
        Route::get('/{type}/{id}/edit', [MasterDataController::class, 'edit'])->name('edit');
        Route::put('/{type}/{id}', [MasterDataController::class, 'update'])->name('update');
        Route::delete('/{type}/{id}', [MasterDataController::class, 'destroy'])->name('destroy');
        Route::post('/{type}/{id}/toggle', [MasterDataController::class, 'toggleActive'])->name('toggle');
    });

});