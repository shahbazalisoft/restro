<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomRoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryManController;
use App\Http\Controllers\Admin\DmVehicleController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RetailerController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\VendorController;


Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('settings', [SystemController::class, 'settings'])->name('settings');
    Route::post('settings', [SystemController::class, 'settings_update']);
    Route::post('settings-password', [SystemController::class, 'settings_password_update'])->name('settings-password');
    Route::get('system-currency', [SystemController::class, 'system_currency'])->name('system_currency');
    Route::get('maintenance-mode', [SystemController::class, 'maintenance_mode'])->name('maintenance-mode');

    # Restuarant Management
    Route::prefix('store')->as('store.')->group(function () {

        Route::middleware(['module:store'])->controller(VendorController::class)->group(function () {
            Route::get('update-application/{id}/{status}', 'update_application')->name('application');
            Route::get('add', 'index')->name('add');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{store}', 'update')->name('update');
            Route::post('update-settings/{store}', 'pdateStoreSettings')->name('update-settings');
            Route::post('update-meta-data/{store}', 'updateStoreMetaData')->name('update-meta-data');
            Route::delete('delete/{store}', 'destroy')->name('delete');
            Route::get('view/{store}/{tab?}/{sub_tab?}', 'view')->name('view');
            Route::get('list', 'list')->name('list');
            Route::get('status/{store}/{status}', 'status')->name('status');
        });

    });

    # Setting
    Route::prefix('business-settings')->as('business-settings.')->middleware(['module:settings'])->controller(BusinessSettingsController::class)->group(function () {
        Route::get('business-setup/{tab?}', 'business_index')->name('business-setup');
        Route::post('update-setup', 'business_setup')->name('update-setup');

        Route::prefix('language')->as('language.')->controller(LanguageController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('add-new', 'store')->name('add-new');
            Route::get('update-status', 'update_status')->name('update-status');
            Route::get('update-default-status', 'update_default_status')->name('update-default-status');
            Route::post('update', 'update')->name('update');
            Route::get('translate/{lang}', 'translate')->name('translate');
            Route::post('translate-submit/{lang}', 'translate_submit')->name('translate-submit');
            Route::post('remove-key/{lang}', 'translate_key_remove')->name('remove-key');
            Route::get('delete/{lang}', 'delete')->name('delete');
            Route::any('auto-translate/{lang}', 'auto_translate')->name('auto-translate');
            Route::get('auto-translate-all/{lang}', 'auto_translate_all')->name('auto_translate_all');
        });
    });
});
