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
use App\Http\Controllers\Wholesaler\WholesalerCartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\Wholesaler\ProductsController;
use App\Http\Controllers\Wholesaler\WholesalerOrderController;
use App\Http\Controllers\Wholesaler\WholesalerPaymentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'))->name('home');

// Authentication
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', fn() => view('auth.login'))->name('login');
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
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Admin Dashboard Route
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin Resources
        Route::resource('workers', WorkerController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->except(['create', 'store']);

        // Supply Requests
        Route::get('supply-requests', [AdminSupplyRequestController::class, 'index'])->name('supply-requests.index');
        Route::get('supply-requests/create', [AdminSupplyRequestController::class, 'create'])->name('supply-requests.create');
        Route::post('supply-requests', [AdminSupplyRequestController::class, 'store'])->name('supply-requests.store');
        Route::get('supply-requests/{supplyRequest}', [AdminSupplyRequestController::class, 'show'])->name('supply-requests.show');
        Route::patch('supply-requests/{supplyRequest}/confirm', [AdminSupplyRequestController::class, 'confirm'])->name('supply-requests.confirm');
        Route::patch('supply-requests/{supplyRequest}/reject', [AdminSupplyRequestController::class, 'reject'])->name('supply-requests.reject');
        Route::patch('supply-requests/{supplyRequest}/fulfill', [AdminSupplyRequestController::class, 'fulfill'])->name('supply-requests.fulfill');

        // Supplier Requests
        Route::get('supplier-requests', [App\Http\Controllers\Admin\SupplierRequestController::class, 'index'])->name('supplier-requests.index');
        Route::get('supplier-requests/{supplierRequest}', [App\Http\Controllers\Admin\SupplierRequestController::class, 'show'])->name('supplier-requests.show');
        Route::post('supplier-requests/{supplierRequest}/approve', [App\Http\Controllers\Admin\SupplierRequestController::class, 'approve'])->name('supplier-requests.approve');
        Route::post('supplier-requests/{supplierRequest}/reject', [App\Http\Controllers\Admin\SupplierRequestController::class, 'reject'])->name('supplier-requests.reject');
        Route::post('supplier-requests/{supplierRequest}/fulfill', [App\Http\Controllers\Admin\SupplierRequestController::class, 'fulfill'])->name('supplier-requests.fulfill');

        // Admin Features
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        
        // AI-Powered Analytics Routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('inventory-optimization', [AnalyticsController::class, 'inventoryOptimization'])->name('inventory-optimization');
            Route::get('sales-prediction', [AnalyticsController::class, 'salesPrediction'])->name('sales-prediction');
            Route::get('customer-lifetime-value', [AnalyticsController::class, 'customerLifetimeValue'])->name('customer-lifetime-value');
            Route::get('market-trends', [AnalyticsController::class, 'marketTrends'])->name('market-trends');
            Route::get('demand-forecast', [AnalyticsController::class, 'demandForecast'])->name('demand-forecast');
            Route::get('customer-segments', [AnalyticsController::class, 'customerSegments'])->name('customer-segments');
            Route::get('top-products', [AnalyticsController::class, 'topProducts'])->name('top-products');
        });
        
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('reports/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
        Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
        // Admin Chat Routes (NEW)
        Route::get('chat', [App\Http\Controllers\Admin\AdminChatController::class, 'index'])->name('chat.index');
        Route::post('chat/send', [App\Http\Controllers\Admin\AdminChatController::class, 'sendMessage'])->name('chat.send');

        // Admin to Wholesaler Chat
        Route::get('chat/wholesaler/{wholesalerId}', [App\Http\Controllers\Admin\AdminChatUserController::class, 'wholesaler'])->name('chat.wholesaler');
        // Admin to Supplier Chat
        Route::get('chat/supplier/{supplierId}', [App\Http\Controllers\Admin\AdminChatUserController::class, 'supplier'])->name('chat.supplier');
        // Admin to Vendor Chat
        Route::get('chat/vendor/{vendorId}', [App\Http\Controllers\Admin\AdminChatUserController::class, 'vendor'])->name('chat.vendor');
        // Send message to user (wholesaler, supplier, vendor)
        Route::post('chat/{userType}/{userId}/send', [App\Http\Controllers\Admin\AdminChatUserController::class, 'sendMessage'])->name('chat.user.send');
        Route::get('chat/unread-count', [App\Http\Controllers\Admin\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
        Route::post('chat/mark-read', [App\Http\Controllers\Admin\ChatController::class, 'markAsRead'])->name('chat.mark-read');
        Route::get('chat/contacts', [App\Http\Controllers\Admin\ChatController::class, 'getContacts'])->name('chat.contacts');

        // Custom chat routes for sidebar dropdown
        Route::get('chat/sent', [App\Http\Controllers\Admin\ChatController::class, 'sentMessages'])->name('chat.sent');
        
        // Additional admin chat routes
        Route::get('chat/admin', [App\Http\Controllers\Admin\ChatController::class, 'chatWithAdmin'])->name('chat.admin');
        Route::get('chat/supplier-admin', [App\Http\Controllers\Admin\ChatController::class, 'chatWithSupplierAdmin'])->name('chat.supplier-admin');
        Route::get('chat/vendor-admin', [App\Http\Controllers\Admin\ChatController::class, 'chatWithVendorAdmin'])->name('chat.vendor-admin');
    });

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')
    ->name('vendor.')
    ->middleware(['auth', 'role:vendor'])
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\VendorValidationController::class, 'dashboard'])->name('dashboard');
        Route::get('/apply', [App\Http\Controllers\VendorValidationController::class, 'showApplicationForm'])->name('apply');
        Route::post('/apply', [App\Http\Controllers\VendorValidationController::class, 'submitApplication'])->name('apply.submit');
        Route::get('/status', [App\Http\Controllers\VendorValidationController::class, 'applicationStatus'])->name('status');
        
        // Chat with Admin
        Route::get('/chat/admin', [App\Http\Controllers\Admin\ChatController::class, 'vendorChatWithAdmin'])->name('chat.admin');
        Route::get('/chat/{userId}', [App\Http\Controllers\Admin\ChatController::class, 'chatWithUser'])->name('chat.with');
        Route::post('/chat/send', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    });

// Admin: View all vendor applications
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/vendor-validation', [App\Http\Controllers\VendorValidationController::class, 'adminIndex'])->name('vendor-validation.index');
        Route::get('/vendor-validation/{application}', [App\Http\Controllers\VendorValidationController::class, 'adminShow'])->name('vendor-validation.show');
        Route::patch('/vendor-validation/{application}/approve', [App\Http\Controllers\VendorValidationController::class, 'approveApplication'])->name('vendor-validation.approve');
        Route::patch('/vendor-validation/{application}/reject', [App\Http\Controllers\VendorValidationController::class, 'rejectApplication'])->name('vendor-validation.reject');
        
        // Validation Requirements Management
        Route::prefix('validation-requirements')->name('validation-requirements.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'store'])->name('store');
            Route::get('/{name}/edit', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'edit'])->name('edit');
            Route::put('/{name}', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'update'])->name('update');
            Route::delete('/{name}', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'destroy'])->name('destroy');
            Route::patch('/{name}/toggle', [App\Http\Controllers\Admin\ValidationRequirementsController::class, 'toggle'])->name('toggle');
        });
        
        // ... other admin routes ...
    });

/*
|--------------------------------------------------------------------------
| Supplier Routes
|--------------------------------------------------------------------------
*/

Route::prefix('supplier')
    ->name('supplier.')
    ->middleware(['auth', 'role:supplier'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
        
        // Requests Management
        Route::prefix('requests')->group(function () {
            Route::get('/', [\App\Http\Controllers\Supplier\RequestController::class, 'index'])
                ->name('requests.index');
            Route::get('/pending', [\App\Http\Controllers\Supplier\RequestController::class, 'pending'])
                ->name('requests.pending');
            Route::get('/confirmed', [\App\Http\Controllers\Supplier\RequestController::class, 'confirmed'])
                ->name('requests.confirmed');
            Route::get('/fulfilled', [\App\Http\Controllers\Supplier\RequestController::class, 'fulfilled'])
                ->name('requests.fulfilled');
            Route::get('/create', [\App\Http\Controllers\Supplier\RequestController::class, 'create'])
                ->name('requests.create');
            Route::post('/', [\App\Http\Controllers\Supplier\RequestController::class, 'store'])
                ->name('requests.store');
            Route::get('/{request}', [\App\Http\Controllers\Supplier\RequestController::class, 'show'])
                ->name('requests.show');
        });

        // Chat with Admin
        Route::get('/chat/admin', [App\Http\Controllers\Admin\ChatController::class, 'supplierChatWithAdmin'])->name('chat.admin');
        Route::get('/chat/{userId}', [App\Http\Controllers\Admin\ChatController::class, 'chatWithUser'])->name('chat.with');
        Route::post('/chat/send', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');

        // Communication
        Route::prefix('communication')->group(function () {
            Route::get('/chat', function () {
                return view('supplier.communication.chat');
            })->name('communication.chat');
        });

        // Profile
        Route::prefix('profile')->group(function () {
            Route::get('/', [\App\Http\Controllers\Supplier\ProfileController::class, 'edit'])
                ->name('profile.edit');
            Route::patch('/', [\App\Http\Controllers\Supplier\ProfileController::class, 'update'])
                ->name('profile.update');
        });
    });

/*
|--------------------------------------------------------------------------
| Wholesaler Routes
|--------------------------------------------------------------------------
*/

Route::prefix('wholesaler')
    ->name('wholesaler.')
    ->middleware(['auth', 'role:wholesaler'])
    ->group(function () {
        // Main Dashboard (summary, analytics, quick links)
        Route::get('/dashboard', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'dashboard'])->name('dashboard');

        // Product Catalog (grid, filters, search)
        Route::get('/catalog', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'catalog'])->name('catalog');

        // Order Placement (form, CSV upload)
        Route::get('/order', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'orderForm'])->name('order.form');
        Route::post('/order', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'submitOrder'])->name('order.submit');
        Route::post('/order/upload-csv', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'uploadCsv'])->name('order.upload_csv');

        // Order Confirmation
        Route::get('/order/confirmation/{order}', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'orderConfirmation'])->name('order.confirmation');

        // Order Tracking (pipeline)
        Route::get('/orders/track', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'orderTracking'])->name('orders.track');

        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'analytics'])->name('analytics');

        // Export (PDF/Excel)
        Route::get('/export/orders/{type}', [App\Http\Controllers\Wholesaler\WholesalerDashboardController::class, 'exportOrders'])->name('export.orders');

        // Product Routes
        Route::resource('products', ProductsController::class, ['only' => ['index', 'show', 'lowStock', 'outOfStock']]);
        Route::get('/products/low-stock', [ProductsController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/out-of-stock', [ProductsController::class, 'outOfStock'])->name('products.out-of-stock');

        // Cart Routes
        Route::post('/cart/add/{productId}', [WholesalerCartController::class, 'add'])->name('cart.add');
        Route::get('/cart', [WholesalerCartController::class, 'show'])->name('cart.show');
        Route::delete('/cart/remove/{id}', [WholesalerCartController::class, 'remove'])->name('cart.remove');

        
        // Order Routes
        Route::get('/orders', [WholesalerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [WholesalerOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/history', [WholesalerOrderController::class, 'history'])->name('orders.history');

        
        // Chat Routes
        Route::get('/chat/admin', [App\Http\Controllers\Admin\ChatController::class, 'wholesalerChatWithAdmin'])->name('chat.admin');
        Route::get('/chat/{userId}', [App\Http\Controllers\Admin\ChatController::class, 'chatWithUser'])->name('chat.with');
        Route::post('/chat/send', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    });



// Vendor chat with admin
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/chat/admin', [App\Http\Controllers\Admin\ChatController::class, 'vendorChatWithAdmin'])->name('vendor.chat.admin');
});

// Wholesaler chat send route
Route::post('wholesaler/chat/send', [App\Http\Controllers\Wholesaler\WholesalerChatController::class, 'sendMessage'])->name('wholesaler.chat.send');
// Supplier chat send route
Route::post('supplier/chat/send', [App\Http\Controllers\Supplier\SupplierChatController::class, 'sendMessage'])->name('supplier.chat.send');
// Vendor chat send route
Route::post('vendor/chat/send', [App\Http\Controllers\Vendor\VendorChatController::class, 'sendMessage'])->name('vendor.chat.send');

// Override Chatify contacts route to use custom controller
Route::get('/chatify/contacts', [App\Http\Controllers\ChatifyMessagesController::class, 'getContacts'])->name('chatify.contacts');
// Override Chatify getUsers endpoint to use custom controller
Route::get('/chatify/getUsers', [App\Http\Controllers\ChatifyMessagesController::class, 'getUsers'])->name('chatify.getUsers');

// Fallback dashboard route for all users
Route::get('/dashboard', function() {
    return redirect()->route('wholesaler.dashboard');
})->name('dashboard');