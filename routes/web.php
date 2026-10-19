<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoffeeShopDashboardController;
use App\Http\Controllers\PetaniDashboardController;
use App\Http\Controllers\TraceabilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Koppee Front-Facing & Traceability)
|--------------------------------------------------------------------------
*/
Route::get('/', [TraceabilityController::class, 'index'])->name('home');
Route::get('/about', [TraceabilityController::class, 'about'])->name('about');
Route::get('/service', [TraceabilityController::class, 'service'])->name('service');
Route::get('/menu', [TraceabilityController::class, 'menu'])->name('menu');
Route::get('/contact', [TraceabilityController::class, 'contact'])->name('contact');

Route::get('/scan', [TraceabilityController::class, 'scan'])->name('scan');
Route::get('/trace/{slug}', [TraceabilityController::class, 'show'])->name('trace.show');

/*
|--------------------------------------------------------------------------
| Authentication & WhatsApp OTP Routes
|--------------------------------------------------------------------------
*/
Route::get('/register-petani', [AuthController::class, 'showPetaniRegister'])->name('register.petani');
Route::post('/register-petani', [AuthController::class, 'registerPetani'])->name('register.petani.submit');

Route::get('/verify-otp/{id_petani}', [AuthController::class, 'showVerifyOtp'])->name('otp.verify.form');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/resend-otp/{id_petani}', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/petani', [AuthController::class, 'loginPetani'])->name('login.petani');
Route::post('/login/coffee-shop', [AuthController::class, 'loginCoffeeShop'])->name('login.coffeeshop');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Portal Petani Routes (With Sidebar Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:petani')->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [PetaniDashboardController::class, 'index'])->name('dashboard');
    
    // Lahan
    Route::get('/lahan', [PetaniDashboardController::class, 'lahanIndex'])->name('lahan.index');
    Route::get('/lahan/create', [PetaniDashboardController::class, 'lahanCreate'])->name('lahan.create');
    Route::post('/lahan/store', [PetaniDashboardController::class, 'lahanStore'])->name('lahan.store');

    // Produksi Green Bean
    Route::get('/produksi', [PetaniDashboardController::class, 'produksiIndex'])->name('produksi.index');
    Route::get('/produksi/create', [PetaniDashboardController::class, 'produksiCreate'])->name('produksi.create');
    Route::post('/produksi/store', [PetaniDashboardController::class, 'produksiStore'])->name('produksi.store');
});

/*
|--------------------------------------------------------------------------
| Portal Coffee Shop / Roastery Routes (With Sidebar Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:coffee_shop')->prefix('coffee-shop')->name('coffeeshop.')->group(function () {
    Route::get('/dashboard', [CoffeeShopDashboardController::class, 'index'])->name('dashboard');

    // Pasokan Petani
    Route::get('/pasokan', [CoffeeShopDashboardController::class, 'pasokanIndex'])->name('pasokan.index');
    Route::post('/terima-produksi/{id_produksi}', [CoffeeShopDashboardController::class, 'terimaProduksi'])->name('terima.produksi');

    // Produksi Diterima & Generate QR
    Route::get('/produksi-diterima', [CoffeeShopDashboardController::class, 'produksiDiterimaIndex'])->name('produksi.diterima');
    Route::post('/generate-qr/{id_produksi}', [CoffeeShopDashboardController::class, 'generateQr'])->name('generate.qr');

    // Kemasan Ber-QR & Label Printing
    Route::get('/kemasan', [CoffeeShopDashboardController::class, 'kemasanIndex'])->name('kemasan.index');
    Route::get('/kemasan/print/{id_kemasan}', [CoffeeShopDashboardController::class, 'printLabel'])->name('kemasan.print');
});
