<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\WorkerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SupplyRequestController as AdminSupplyRequestController; // Added import
use App\Http\Controllers\SupplierDashboardController;
use App\Http\Controllers\User\userproductDisplayController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Always show the welcome page as landing; redirect only after authentication
    return view('welcome');
})->middleware('web');

// Ensure user is authenticated for dashboard links (for admins only)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'wholesaler') {
            return view('wholesaler.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    // Sent Requests (Supplier, visible to admin)
    Route::get('/sent-requests', [App\Http\Controllers\SupplierDashboardController::class, 'listRequests'])->name('supplier.requests.list');
    // Reports (Analytics)
    Route::get('/reports', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
});

// Allow suppliers to see their own sent requests and status
Route::middleware(['auth'])->group(function () {
    Route::get('/sent-requests', [App\Http\Controllers\SupplierDashboardController::class, 'listRequests'])->name('supplier.requests.list.supplier');
});

// Ensure supplier requests and analytics routes require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/supplier/requests', [\App\Http\Controllers\SupplierDashboardController::class, 'listRequests'])->name('supplier.requests.list');
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Dashboard Route
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('auth'); // Assuming admin dashboard also requires auth

    // Admin Worker Routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () { // Consider adding admin role middleware here
        Route::resource('workers', WorkerController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->except(['create', 'store']);
        
        // Admin Supply Request Management
        Route::get('supply-requests', [AdminSupplyRequestController::class, 'index'])->name('supply-requests.index');
        Route::get('supply-requests/{supplyRequest}', [AdminSupplyRequestController::class, 'show'])->name('supply-requests.show');
        Route::patch('supply-requests/{supplyRequest}/confirm', [AdminSupplyRequestController::class, 'confirm'])->name('supply-requests.confirm');
        Route::patch('supply-requests/{supplyRequest}/reject', [AdminSupplyRequestController::class, 'reject'])->name('supply-requests.reject');
        Route::patch('supply-requests/{supplyRequest}/fulfill', [AdminSupplyRequestController::class, 'fulfill'])->name('supply-requests.fulfill');

        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});

// Supplier Routes
Route::middleware(['auth'])->prefix('supplier')->name('supplier.')->group(function () {
    // Route::middleware(['role:user'])->group(function () { // Assuming 'user' role is for suppliers
        Route::get('dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
        Route::get('requests/create', [SupplierDashboardController::class, 'createRequestForm'])->name('requests.create');
        Route::post('requests', [SupplierDashboardController::class, 'storeRequest'])->name('requests.store');
        Route::get('requests', [SupplierDashboardController::class, 'listRequests'])->name('requests.list');
        Route::get('requests/confirmed', [SupplierDashboardController::class, 'listConfirmedRequests'])->name('requests.confirmed');
        Route::get('requests/{supplyRequest}', [SupplierDashboardController::class, 'showRequest'])->name('requests.show');
    // });
});

// Wholesaler-facing e-commerce routes (authenticated, role: wholesaler)
Route::middleware(['auth', 'role:wholesaler'])->prefix('wholesaler')->group(function () {
    Route::get('/shop', [userproductDisplayController::class, 'index'])->name('products.index');
    Route::get('/products', [userproductDisplayController::class, 'index']);
    Route::get('/products/{id}', [userproductDisplayController::class, 'show'])->name('products.show');

    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
});

require __DIR__.'/auth.php';
