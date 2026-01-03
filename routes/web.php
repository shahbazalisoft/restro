<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;

Route::controller(HomeController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::get('about-us', 'about_us')->name('about-us');
    Route::get('privacy-policy', 'privacy_policy')->name('privacy-policy');
    Route::get('terms-and-conditions', 'terms_and_conditions')->name('terms-and-conditions');
    Route::get('contact-us', 'contact_us')->name('contact-us');
    Route::get('lang/{locale}', 'lang')->name('lang');
    Route::get('newsletter/subscribe', 'newsLetterSubscribe')->name('newsletter.subscribe');


});

Route::get('menu', [MenuController::class, 'index'])->name('home-menu');

Route::prefix('vendor')->as('restaurant.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('apply', 'create')->name('create');
        Route::post('apply', 'store')->name('store');
    });
});


Route::controller(LoginController::class)->group(function () {
    Route::get('login/{tab}', 'login')->name('login');
    Route::post('login_submit', 'submit')->name('login_post');
    Route::get('logout', 'logout')->name('logout');
    Route::get('/reload-captcha', 'reloadCaptcha')->name('reload-captcha');
    Route::get('reset-password', 'reset_password_request')->name('reset-password');
    Route::get('/vendor-reset-password', 'vendor_reset_password_request')->name('vendor-reset-password');

});