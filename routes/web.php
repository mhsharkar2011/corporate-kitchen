<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [App\Http\Controllers\ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::patch('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::patch('/profile/notifications', [App\Http\Controllers\ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Order routes
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // User management routes
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::patch('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/impersonate', [App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('users/stop-impersonate', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonate'])->name('users.stop-impersonate');

        // Menu management
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::patch('/menus/{menu}/toggle', [MenuController::class, 'toggleAvailability'])->name('menus.toggle');

        // Order management routes
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/confirm', [App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('orders.confirm');
        Route::patch('/orders/{order}/prepare', [App\Http\Controllers\Admin\OrderController::class, 'startPreparing'])->name('orders.prepare');
        Route::patch('/orders/{order}/deliver', [App\Http\Controllers\Admin\OrderController::class, 'deliver'])->name('orders.deliver');
        Route::patch('/orders/{order}/close', [App\Http\Controllers\Admin\OrderController::class, 'close'])->name('orders.close');
        Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/edit', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    });


    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
        Route::resource('suppliers', App\Http\Controllers\Admin\SupplierController::class);
        Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);
        Route::resource('reports', App\Http\Controllers\Admin\ReportController::class);
        Route::resource('settings', App\Http\Controllers\Admin\SettingController::class);
    });

    // Supplier routes
    Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        Route::resource('orders', App\Http\Controllers\Supplier\OrderController::class);
        Route::resource('inventory', App\Http\Controllers\Supplier\InventoryController::class);
        Route::resource('deliveries', App\Http\Controllers\Supplier\DeliveryController::class);
        Route::resource('products', App\Http\Controllers\Supplier\ProductController::class);
        Route::resource('payments', App\Http\Controllers\Supplier\PaymentController::class);
    });

    // Supervisor routes
    Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::resource('orders', App\Http\Controllers\Supervisor\OrderController::class);
        Route::get('/kitchen', [App\Http\Controllers\Supervisor\KitchenController::class, 'index'])->name('kitchen.index');
        Route::resource('staff', App\Http\Controllers\Supervisor\StaffController::class);
        Route::resource('inventory', App\Http\Controllers\Supervisor\InventoryController::class);
        Route::resource('quality', App\Http\Controllers\Supervisor\QualityControlController::class);
    });

    // Client/User routes
    Route::middleware(['auth', 'role:client'])->prefix('user')->name('user.')->group(function () {
        Route::resource('subscriptions', App\Http\Controllers\User\SubscriptionController::class);
        Route::resource('wishlist', App\Http\Controllers\User\WishlistController::class);
        Route::resource('addresses', App\Http\Controllers\User\AddressController::class);
        Route::resource('payments', App\Http\Controllers\User\PaymentController::class);
        Route::get('/rewards', [App\Http\Controllers\User\RewardController::class, 'index'])->name('rewards.index');
    });
});

// Auth routes (provided by Laravel Breeze)
require __DIR__ . '/auth.php';
