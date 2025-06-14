<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WholesalerController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SupplyRequestController as AdminSupplyRequestController;

use App\Http\Controllers\SupplierDashboardController;

use App\Http\Controllers\User\userproductDisplayController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('home');

// Authentication
Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']); // 👈 Handles redirection by role

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Profile Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
        Route::resource('workers', WorkerController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->except(['create', 'store']);

        Route::get('supply-requests', [AdminSupplyRequestController::class, 'index'])->name('supply-requests.index');
        Route::get('supply-requests/{supplyRequest}', [AdminSupplyRequestController::class, 'show'])->name('supply-requests.show');
        Route::patch('supply-requests/{supplyRequest}/confirm', [AdminSupplyRequestController::class, 'confirm'])->name('supply-requests.confirm');
        Route::patch('supply-requests/{supplyRequest}/reject', [AdminSupplyRequestController::class, 'reject'])->name('supply-requests.reject');
        Route::patch('supply-requests/{supplyRequest}/fulfill', [AdminSupplyRequestController::class, 'fulfill'])->name('supply-requests.fulfill');

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| Supplier Routes
|--------------------------------------------------------------------------
*/

Route::prefix('supplier')
    ->name('supplier.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/create', [SupplierDashboardController::class, 'createRequestForm'])->name('requests.create');
        Route::post('/requests', [SupplierDashboardController::class, 'storeRequest'])->name('requests.store');
        Route::get('/requests', [SupplierDashboardController::class, 'listRequests'])->name('requests.list');
        Route::get('/requests/confirmed', [SupplierDashboardController::class, 'listConfirmedRequests'])->name('requests.confirmed');
        Route::get('/requests/{supplyRequest}', [SupplierDashboardController::class, 'showRequest'])->name('requests.show');
    });

/*
|--------------------------------------------------------------------------
| Wholesaler Routes
|--------------------------------------------------------------------------
*/

Route::prefix('wholesaler')
    ->name('wholesaler.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [WholesalerController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [userproductDisplayController::class, 'show'])->name('products.show');

        Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
        Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
    });
