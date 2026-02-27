<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset-counters', [SettingsController::class, 'resetCounters'])->name('settings.reset');
    Route::post('/settings/ecopay/decrement-plastic', [SettingsController::class, 'decrementPlastic'])->name('settings.ecopay.decrementPlastic');
    Route::post('/settings/ecopay/decrement-metal', [SettingsController::class, 'decrementMetal'])->name('settings.ecopay.decrementMetal');
    Route::post('/settings/ecopay/add-coins', [SettingsController::class, 'addCoins'])->name('settings.ecopay.addCoins');
    Route::delete('/activity-log/{id}', [ActivityLogController::class, 'delete'])->name('activity-log.delete');
});

require __DIR__.'/auth.php';
