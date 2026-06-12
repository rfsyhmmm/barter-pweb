<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\InventoryController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// ==========================================
// RUTE PUBLIK (Bisa diakses siapa saja)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');


// ==========================================
// RUTE GUEST (Hanya untuk yang BELUM login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});


// ==========================================
// RUTE AUTH (Hanya untuk yang SUDAH login)
// ==========================================
Route::middleware('auth')->group(function () {
    // Autentikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Inventory Management
    Route::resource('inventory', InventoryController::class);
    Route::patch('/inventory/{id}/publish', [InventoryController::class, 'publish'])->name('inventory.publish');

    // Order & Cancel
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // TAMBAHKAN DUA BARIS INI:
    Route::patch('/orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::patch('/orders/{id}/reject', [OrderController::class, 'reject'])->name('orders.reject');

    // Propose Trade
    Route::get('/propose-trade/{id}', [TradeController::class, 'propose'])->name('trade.propose');
    Route::post('/propose-trade', [TradeController::class, 'store'])->name('trade.store');

    Route::delete('/orders/{id}/history', [\App\Http\Controllers\OrderController::class, 'destroyHistory'])->name('orders.destroyHistory');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Cart Management
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');

    Route::delete('/orders/clear-rejected', [\App\Http\Controllers\OrderController::class, 'clearRejected'])->name('orders.clearRejected');

    Route::patch('/orders/{id}/cancel-deal', [OrderController::class, 'cancelDeal'])->name('orders.cancelDeal');

    // Rute Khusus Admin (Dilindungi middleware 'auth' dan 'admin')
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Halaman Dashboard Admin
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    // show item
    Route::get('/item/{id}', [\App\Http\Controllers\AdminController::class, 'showItem'])->name('item.show');
    // Fitur Takedown Barang
    Route::patch('/item/{id}/takedown', [\App\Http\Controllers\AdminController::class, 'takedownItem'])->name('item.takedown');
    
    Route::patch('/user/{id}/toggle-ban', [\App\Http\Controllers\AdminController::class, 'toggleUserBan'])->name('user.toggleBan');

    // Detail untuk klik baris tabel
    Route::get('/user/{id}', [\App\Http\Controllers\AdminController::class, 'showUser'])->name('user.show');
    Route::get('/report/{id}', [\App\Http\Controllers\AdminController::class, 'showReport'])->name('report.show');
});
// Fitur Report Barang
    Route::get('/item/{id}/report', [\App\Http\Controllers\ReportController::class, 'create'])->name('report.create');
    Route::post('/item/{id}/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
});