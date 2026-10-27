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
Route::get('/donasi', [TraceabilityController::class, 'donasi'])->name('donasi');
Route::get('/menu', [TraceabilityController::class, 'menu'])->name('menu');
Route::get('/contact', [TraceabilityController::class, 'contact'])->name('contact');
Route::post('/contact', [TraceabilityController::class, 'contactSend'])->name('contact.send');

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
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/login/petani', [AuthController::class, 'loginPetani'])->name('login.petani');
Route::post('/login/coffee-shop', [AuthController::class, 'loginCoffeeShop'])->name('login.coffeeshop');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
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
    Route::get('/lahan/{id_lahan}/edit', [PetaniDashboardController::class, 'lahanEdit'])->name('lahan.edit');
    Route::put('/lahan/{id_lahan}', [PetaniDashboardController::class, 'lahanUpdate'])->name('lahan.update');

    // Produksi Green Bean
    Route::get('/produksi', [PetaniDashboardController::class, 'produksiIndex'])->name('produksi.index');
    Route::get('/produksi/create', [PetaniDashboardController::class, 'produksiCreate'])->name('produksi.create');
    Route::post('/produksi/store', [PetaniDashboardController::class, 'produksiStore'])->name('produksi.store');
    Route::get('/produksi/{id_produksi}/edit', [PetaniDashboardController::class, 'produksiEdit'])->name('produksi.edit');
    Route::put('/produksi/{id_produksi}', [PetaniDashboardController::class, 'produksiUpdate'])->name('produksi.update');

    // Panen Ceri
    Route::get('/ceri', [PetaniDashboardController::class, 'ceriIndex'])->name('ceri.index');
    Route::get('/ceri/create', [PetaniDashboardController::class, 'ceriCreate'])->name('ceri.create');
    Route::post('/ceri/store', [PetaniDashboardController::class, 'ceriStore'])->name('ceri.store');
    Route::get('/ceri/{id_panen_ceri}/edit', [PetaniDashboardController::class, 'ceriEdit'])->name('ceri.edit');
    Route::put('/ceri/{id_panen_ceri}', [PetaniDashboardController::class, 'ceriUpdate'])->name('ceri.update');

    // Profil Petani
    Route::get('/profile', [PetaniDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [PetaniDashboardController::class, 'profileUpdate'])->name('profile.update');
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
    Route::post('/kemasan/{id_kemasan}/toggle', [CoffeeShopDashboardController::class, 'toggleStatus'])->name('kemasan.toggle');
    Route::get('/kemasan/print/{id_kemasan}', [CoffeeShopDashboardController::class, 'printLabel'])->name('kemasan.print');
});

/*
|--------------------------------------------------------------------------
| Portal Super Admin Routes (Monitoring Panen & User Management)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminDashboardController::class, 'usersIndex'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::post('/users/verify/{id_petani}', [\App\Http\Controllers\AdminDashboardController::class, 'verifyPetani'])->name('users.verify');
    Route::post('/users/toggle-status/{type}/{id}', [\App\Http\Controllers\AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users/reset-password/{type}/{id}', [\App\Http\Controllers\AdminDashboardController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{type}/{id}', [\App\Http\Controllers\AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

    // Kotak Masuk Pesan Kontak Pengunjung
    Route::get('/pesan', [\App\Http\Controllers\AdminDashboardController::class, 'pesanIndex'])->name('pesan.index');
    Route::post('/pesan/{id_pesan}/toggle-read', [\App\Http\Controllers\AdminDashboardController::class, 'pesanToggleRead'])->name('pesan.toggle-read');
    Route::delete('/pesan/{id_pesan}', [\App\Http\Controllers\AdminDashboardController::class, 'pesanDestroy'])->name('pesan.destroy');

    // WhatsApp Gateway Management (Sidecar QR Pairing)
    Route::get('/whatsapp', [\App\Http\Controllers\WhatsAppWebController::class, 'index'])->name('whatsapp.index');
    Route::get('/whatsapp/status', [\App\Http\Controllers\WhatsAppWebController::class, 'status'])->name('whatsapp.status');
    Route::post('/whatsapp/start', [\App\Http\Controllers\WhatsAppWebController::class, 'start'])->name('whatsapp.start');
    Route::post('/whatsapp/logout', [\App\Http\Controllers\WhatsAppWebController::class, 'logout'])->name('whatsapp.logout');
});


