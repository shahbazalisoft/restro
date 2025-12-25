<?php

use App\Http\Controllers\Vendor\ConversationController;
use App\Http\Controllers\Vendor\DashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'vendor'])->prefix('vendor')->as('vendor.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('site_direction', 'site_direction_vendor')->name('site_direction');
        Route::get('store-token', 'updateDeviceToken')->name('store.token');
        Route::get('order-stats', 'order_stats')->name('dashboard.order-stats');
    });

    Route::prefix('message')->as('message.')->controller(ConversationController::class)->group(function () {
        Route::get('list', 'list')->name('list');
        Route::post('store/{user_id}/{user_type}', 'store')->name('store');
        Route::get('view/{conversation_id}/{user_id}', 'view')->name('view');
    });
});
