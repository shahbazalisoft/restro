<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Vendor\BusinessSettingsController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\ConversationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ItemController;
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

    # Item Management
    Route::prefix('item')->as('item.')->middleware(['module:item', 'subscription:item'])->controller(ItemController::class)->group(function () {
        Route::get('add-new', 'index')->name('add-new');
        Route::post('variant-combination', 'variant_combination')->name('variant-combination');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('list', 'list')->name('list');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('status/{id}/{status}', 'status')->name('status');
        Route::post('search', 'search')->name('search');
        Route::get('view/{id}', 'view')->name('view');
        Route::get('remove-image', 'remove_image')->name('remove-image');
        Route::get('get-categories', 'get_categories')->name('get-categories');
        Route::get('recommended/{id}/{status}', 'recommended')->name('recommended');
        Route::get('pending/item/list', 'pending_item_list')->name('pending_item_list');
        Route::get('requested/item/view/{id}', 'requested_item_view')->name('requested_item_view');

        Route::get('product-gallery', 'product_gallery')->name('product_gallery');


        //Mainul
        Route::get('get-variations', 'get_variations')->name('get-variations');
        Route::get('stock-limit-list', 'stock_limit_list')->name('stock-limit-list');
        Route::get('get-stock', 'get_stock')->name('get_stock');
        Route::post('stock-update', 'stock_update')->name('stock-update');

        Route::post('food-variation-generate', 'food_variation_generator')->name('food-variation-generate');
        Route::post('variation-generate', 'variation_generator')->name('variation-generate');
        
        //Import and export
        Route::get('bulk-import', 'bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'bulk_import_data');
        Route::get('bulk-export', 'bulk_export_index')->name('bulk-export-index');
        Route::post('bulk-export', 'bulk_export_data')->name('bulk-export');
        Route::get('flash-sale', 'flash_sale')->name('flash_sale');
        Route::get('get-brand-list', 'getBrandList')->name('getBrandList');

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
        Route::get('get-all', 'get_all')->name('get-all');
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
