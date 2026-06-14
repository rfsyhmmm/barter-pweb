<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\InventoryController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;


// RUTE PUBLIK (Bisa diakses siapa saja)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/faq', 'pages.faq')->name('faq');
Route::get('/user/{id}', [ProfileController::class, 'show'])->name('user.show');


// RUTE GUEST (Hanya untuk yang BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});


// RUTE AUTH (Hanya untuk yang SUDAH login)
Route::middleware(['auth', 'banned'])->group(function () {
    // Autentikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Inventory Management
    Route::resource('inventory', InventoryController::class);
    Route::patch('/inventory/{id}/publish', [InventoryController::class, 'publish'])->name('inventory.publish');

    // ORDER & TRADE MANAGEMENT
    // Halaman utama Orders (Tawaran Masuk & Keluar)
    Route::get('/orders', [TradeController::class, 'index'])->name('orders.index');
    
    // Propose Trade / Ajukan Barter
    Route::get('/trade/propose/{item_id}', [TradeController::class, 'create'])->name('trade.propose');
    Route::post('/trade/store', [TradeController::class, 'store'])->name('trade.store');

    // Aksi Transaksi Penjual & Pembeli (2-Step Acceptance & Escrow)
    Route::patch('/trade/{id}/negotiate', [TradeController::class, 'negotiate'])->name('trade.negotiate');
    Route::patch('/trade/{id}/reject', [TradeController::class, 'reject'])->name('trade.reject');
    Route::patch('/trade/{id}/invoice', [TradeController::class, 'invoice'])->name('trade.invoice');
    Route::patch('/trade/{id}/upload-proof', [TradeController::class, 'uploadProof'])->name('trade.uploadProof');
    Route::patch('/trade/{id}/complete', [TradeController::class, 'complete'])->name('trade.complete');
    Route::delete('/trade/{id}/cancel', [TradeController::class, 'cancel'])->name('trade.cancel');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart Management
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Fitur Report Barang
    Route::get('/item/{id}/report', [ReportController::class, 'create'])->name('report.create');
    Route::post('/item/{id}/report', [ReportController::class, 'store'])->name('report.store');

    

    // RUTE KHUSUS ADMIN
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Halaman Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Fitur Barang
        Route::get('/item/{id}', [AdminController::class, 'showItem'])->name('item.show');
        Route::patch('/item/{id}/takedown', [AdminController::class, 'takedownItem'])->name('item.takedown');
        
        // Fitur Pengguna & Laporan
        Route::patch('/user/{id}/toggle-ban', [AdminController::class, 'toggleUserBan'])->name('user.toggleBan');
        Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('user.show');
        Route::get('/report/{id}', [AdminController::class, 'showReport'])->name('report.show');
    });
});