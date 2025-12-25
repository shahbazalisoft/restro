<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AttributeController;
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
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\VendorController;


Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

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
    
    Route::get('order/generate-invoice/{id}', [OrderController::class, 'generate_invoice'])->name('order.generate-invoice');
    Route::get('order/print-invoice/{id}', [OrderController::class, 'print_invoice'])->name('order.print-invoice');
    Route::get('order/status', [OrderController::class, 'status'])->name('order.status');
    Route::get('order/offline-payment', [OrderController::class, 'offline_payment'])->name('order.offline_payment');
    Route::prefix('order')->as('order.')->middleware(['module:order'])->controller(OrderController::class)->group(function () {
        Route::get('list/{status}', 'list')->name('list');
        Route::get('details/{id}', 'details')->name('details');
        Route::get('all-details/{id}', 'all_details')->name('all-details');
        Route::get('view/{id}', 'view')->name('view');
        Route::post('update-shipping/{order}', 'update_shipping')->name('update-shipping');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('add-delivery-man/{order_id}/{delivery_man_id}', 'add_delivery_man')->name('add-delivery-man');
        Route::get('payment-status', 'payment_status')->name('payment-status');

        Route::post('add-payment-ref-code/{id}', 'add_payment_ref_code')->name('add-payment-ref-code');
        Route::post('add-order-proof/{id}', 'add_order_proof')->name('add-order-proof');
        Route::get('remove-proof-image', 'remove_proof_image')->name('remove-proof-image');
        Route::get('store-filter/{store_id}', 'restaurnt_filter')->name('store-filter');
        Route::get('filter/reset', 'filter_reset');
        Route::post('filter', 'filter')->name('filter');
        Route::get('search', 'search')->name('search');
        Route::post('store/search', 'store_order_search')->name('store-search');
        Route::get('store/export', 'store_order_export')->name('store-export');
        //order update
        Route::post('add-to-cart', 'add_to_cart')->name('add-to-cart');
        Route::post('remove-from-cart', 'remove_from_cart')->name('remove-from-cart');
        Route::get('update/{order}', 'update')->name('update');
        Route::get('edit-order/{order}', 'edit')->name('edit');
        Route::get('quick-view', 'quick_view')->name('quick-view');
        Route::get('quick-view-cart-item', 'quick_view_cart_item')->name('quick-view-cart-item');
        Route::get('export-orders/{file_type}/{status}/{type}', 'export_orders')->name('export');
        Route::get('offline/payment/list/{status}', 'offline_verification_list')->name('offline_verification_list');
    });
    // Refund
    Route::prefix('refund')->as('refund.')->middleware(['module:order'])->controller(OrderController::class)->group(function () {
        Route::get('settings', 'refund_settings')->name('refund_settings');
        Route::get('refund_mode', 'refund_mode')->name('refund_mode');
        Route::post('refund_reason', 'refund_reason')->name('refund_reason');
        Route::get('/status/{id}/{status}', 'reason_status')->name('reason_status');
        Route::put('reason_edit/', 'reason_edit')->name('reason_edit');
        Route::delete('reason_delete/{id}', 'reason_delete')->name('reason_delete');
        Route::put('order_refund_rejection/', 'order_refund_rejection')->name('order_refund_rejection');
        Route::get('/{status}', 'list')->name('refund_attr');
    });
    #Category Management
    Route::prefix('category')->as('category.')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/status/{id}/{status}', 'status')->name('status');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        Route::get('/update-priority/{category}', 'update_priority')->name('priority');
        Route::get('remove-image', 'remove_image')->name('remove-image');
        Route::get('view/{id}', 'view')->name('view');
        Route::get('get-all', 'get_all')->name('get-all');
        #SubCategory
        Route::get('/sub-category', 'sub_index')->name('sub-category');
        Route::get('/sub-category/add', 'sub_add')->name('add-sub-category');
        Route::post('/sub-category/store', 'sub_store')->name('store-sub-category');
        Route::get('/sub-category/edit/{id}', 'sub_edit')->name('edit-sub-category');
    });
    #Attributes Management
    Route::prefix('attribute')->as('attribute.')->controller(AttributeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        // Route::get('/add-new', 'add')->name('add');
        Route::post('/store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('export-attributes', 'export_attributes')->name('export-attributes');
    });
    #Unit Management
    Route::prefix('unit')->as('unit.')->controller(UnitController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('export-attributes', 'export_attributes')->name('export-attributes');
    });
    #Item Management
    Route::get('item/variant-price', [OrderController::class, 'variant_price'])->name('item.variant-price');
    Route::prefix('item')->as('item.')->controller(ItemController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list', 'list')->name('list');
        Route::get('/add-new', 'index')->name('add-new');
        Route::post('/store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::post('update/{id}', 'update')->name('update');


        Route::get('remove-image', 'remove_image')->name('remove-image');
        Route::post('/variant-combination', 'variant_combination')->name('variant-combination');
        Route::get('/product-gallery', 'product_gallery')->name('product_gallery');
        Route::get('view/{id}', 'view')->name('view');
        Route::get('status/{id}/{status}', 'status')->name('status');

        //ajax request
        Route::get('/get-categories', 'get_categories')->name('get-categories');
        Route::get('/get-items', 'get_items')->name('getitems');
        Route::get('/variation-generate', 'variation_generator')->name('variation-generate');
        // Route::get('/export', 'export')->name('export');
        Route::get('new/item/list', 'approval_list')->name('approval_list');
    });
    Route::get('store/view/{id}', [VendorController::class, 'view'])->name('store.view');
    Route::get('store/get-stores', [VendorController::class, 'get_stores'])->name('get-stores');
    Route::get('module/{id}', [ModuleController::class, 'show'])->name('show');

    #Wholeseler Management
    Route::prefix('wholesaler')->as('wholesaler.')->controller(VendorController::class)->group(function () {
        Route::get('list', 'list')->name('list');
        Route::get('view/{store}/{tab?}/{sub_tab?}', 'view')->name('view');
        Route::get('status/{store}/{status}', 'status')->name('status');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{store}', 'destroy')->name('delete');
        Route::get('pending-requests', 'pending_requests')->name('pending-requests');
        Route::get('deny-requests', 'deny_requests')->name('deny-requests');
        Route::get('update-application/{id}/{status}', 'update_application')->name('application');
        Route::get('download/{id}/{ids}', 'docDownload')->name('doc-download');
    });

    #Retailer Management
    Route::prefix('retailer')->as('retailer.')->controller(RetailerController::class)->group(function () {
        Route::get('list', 'retailer_list')->name('list');
        Route::get('view/{user_id}', 'view')->name('view');
        // Route::get('view/{store}/{tab?}/{sub_tab?}', 'view')->name('view');
        // Route::get('status/{store}/{status}', 'status')->name('status');
        Route::get('status/{customer}/{status}file-manager', 'status')->name('status');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{store}', 'destroy')->name('delete');
        Route::get('pending-requests', 'pending_requests')->name('pending-requests');
        Route::get('deny-requests', 'deny_requests')->name('deny-requests');
        Route::get('pending-requests/{id}/{status}', 'update_request')->name('update-request');
        Route::get('export', 'export')->name('export');
        Route::get('search', 'search')->name('search');
        Route::get('order-export', 'customer_order_export')->name('order-export');
    });

    #Employee Management
    Route::prefix('custom-role')->as('custom-role.')->controller(CustomRoleController::class)->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'distroy')->name('delete');
        Route::post('search', 'search')->name('search');
    });
    Route::prefix('employee')->as('employee.')->controller(EmployeeController::class)->group(function () {
        Route::get('add-new', 'add_new')->name('add-new');
        Route::post('add-new', 'store');
        Route::get('list', 'list')->name('list');
        Route::get('update/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'distroy')->name('delete');
        Route::post('search', 'search')->name('search');
        Route::get('export', 'export')->name('export');
    });
    // Delivery Man Main Routes
    Route::prefix('delivery-man')->as('delivery-man.')->controller(DeliveryManController::class)->group(function () {
        Route::get('add', 'index')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('list', 'list')->name('list');
        Route::get('new', 'new_delivery_man')->name('new');
        Route::get('deny', 'deny_delivery_man')->name('deny');
        Route::get('preview/{id}/{tab?}', 'preview')->name('preview');
        Route::get('status/{id}/{status}', 'status')->name('status');
        Route::get('earning/{id}/{status}', 'earning')->name('earning');
        Route::get('update-application/{id}/{status}', 'update_application')->name('application');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::post('search', 'search')->name('search');
        Route::post('active-search', 'active_search')->name('active-search');

        Route::prefix('reviews')->as('reviews.')->controller(DeliveryManController::class)->group(function () {
            Route::get('list', 'reviews_list')->name('list');
            Route::get('export', 'reviews_export')->name('export');
            Route::get('search', 'review_search')->name('search');
            Route::get('status/{id}/{status}', 'reviews_status')->name('status');
        });

        // Delivery Man Vehicle Routes
        Route::prefix('vehicle')->as('vehicle.')->controller(DmVehicleController::class)->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('add', 'create')->name('create');
            Route::get('status/{vehicle}/{status}', 'status')->name('status');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('store', 'store')->name('store');
            Route::post('update/{vehicle}', 'update')->name('update');
            Route::delete('delete', 'destroy')->name('delete');
            Route::get('view/{vehicle}', 'view')->name('view');
        });
    });

    Route::prefix('dashboard-stats')->as('dashboard-stats.')->controller(DashboardController::class)->group(function () {
            Route::post('order', 'order')->name('order');
            Route::post('zone', 'zone')->name('zone');
            Route::post('user-overview', 'user_overview')->name('user-overview');
            Route::post('commission-overview', 'commission_overview')->name('commission-overview');
            Route::post('business-overview', 'business_overview')->name('business-overview');

    });
});
