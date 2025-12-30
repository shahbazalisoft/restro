<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Vendor\BusinessSettingsController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\ConversationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Vendor\RestaurantController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'vendor'])->prefix('vendor')->as('vendor.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('site_direction', 'site_direction_vendor')->name('site_direction');
        Route::get('store-token', 'updateDeviceToken')->name('store.token');
        Route::get('order-stats', 'order_stats')->name('dashboard.order-stats');
    });

    # Menu Management
    Route::prefix('menu')->as('category.')->middleware(['module:category', 'subscription:category'])->controller(CategoryController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'distroy')->name('delete');
        Route::get('status/{id}/{status}', 'updateStatus')->name('status');
        Route::get('sub-menu-list', 'sub_index')->name('sub-index');
        Route::get('sub-menu/edit', 'sub_edit')->name('sub-edit');
        Route::post('sub-menu/update', 'sub_update')->name('sub-update');
    });
    
    # Business Setting Management
    Route::prefix('business-settings')->as('business-settings.')->controller(BusinessSettingsController::class)->group(function () {
        Route::middleware(['module:store_setup', 'subscription:store_setup'])->group(function () {
            Route::get('store-setup', 'store_index')->name('store-setup');
            Route::post('add-schedule', 'add_schedule')->name('add-schedule');
            Route::get('remove-schedule/{store_schedule}', 'remove_schedule')->name('remove-schedule');
            Route::get('update-active-status', 'active_status')->name('update-active-status');
            Route::post('update-setup/{store}', 'store_setup')->name('update-setup');
            Route::post('update-meta-data/{store}', 'updateStoreMetaData')->name('update-meta-data');
            Route::get('toggle-settings-status/{store}/{status}/{menu}', 'store_status')->name('toggle-settings');
        });
        Route::middleware(['module:notification_setup', 'subscription:notification_setup'])->group(function () {
            Route::get('notification-setup', 'notification_index')->name('notification-setup');
            Route::get('notification-status-change/{key}/{type}', 'notification_status_change')->name('notification_status_change');
        });
    });

    Route::prefix('store')->as('shop.')->middleware(['module:my_shop', 'subscription:my_shop'])->controller(RestaurantController::class)->group(function () {
        Route::get('view', 'view')->name('view');
        Route::get('edit', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-message', 'update_message')->name('update-message');
    });

    Route::get('lang/{locale}', [LanguageController::class, 'lang'])->name('lang');

    Route::prefix('profile')->as('profile.')->middleware(['module:profile', 'subscription:profile'])->controller(ProfileController::class)->group(function () {
        Route::get('view', 'view')->name('view');
        Route::post('update', 'update')->name('update');
        Route::post('settings-password', 'settings_password_update')->name('settings-password');
    });
});
