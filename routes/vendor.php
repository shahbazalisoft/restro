<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Vendor\ConversationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'vendor'])->prefix('vendor')->as('vendor.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');

        Route::get('site_direction', 'site_direction_vendor')->name('site_direction');
        Route::get('store-token', 'updateDeviceToken')->name('store.token');
        Route::get('order-stats', 'order_stats')->name('dashboard.order-stats');
    });
    
    Route::get('lang/{locale}', [LanguageController::class, 'lang'])->name('lang');

    Route::prefix('profile')->as('profile.')->middleware(['module:profile','subscription:profile'])->controller(ProfileController::class)->group(function () {
        Route::get('view', 'view')->name('view');
        Route::post('update', 'update')->name('update');
        Route::post('settings-password', 'settings_password_update')->name('settings-password');
    });
    
});
