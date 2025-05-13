<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\OrderController;

// Home or Welcome page
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Dashboard
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// About Us page
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

// FAQ page
Route::get('/faq', function () {
    return Inertia::render('Faq', [
        'description' => 'Find answers to common questions about our services'
    ]);
})->name('faq');

// Services pages
Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/hospital', [\App\Http\Controllers\ServiceController::class, 'hospitalInfo'])->name('services.hospital');
Route::get('/services/telemedicine', [\App\Http\Controllers\ServiceController::class, 'telemedicine'])->name('services.telemedicine');

// Products pages
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/review', [\App\Http\Controllers\ProductController::class, 'review'])
    ->middleware(['auth'])->name('products.review');

// Cart routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{productId}/add', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/{cart}/update', [\App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'empty'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    // Orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    // Checkout process (order placement)
    Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
});

// Support page
Route::middleware(['auth'])->group(function () {
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
});

// Blogs (public)
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');

// Prescription upload
Route::middleware(['auth'])->group(function () {
    Route::get('/upload-prescription', [PrescriptionController::class, 'index'])->name('prescription.index');
    Route::post('/prescription/upload', [PrescriptionController::class, 'upload'])->name('prescription.upload');
    Route::get('/prescription/view', [PrescriptionController::class, 'view'])->name('prescription.view');
});

// -------------------- ADMIN API ROUTES --------------------
Route::prefix('admin')->group(function () {

    // Admin Dashboard API for Vue SPA
    Route::get('/dashboard-stats', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.stats');
    Route::get('/login', function () {
        return Inertia::render('admin/AdminLogin');
    })->name('admin.login');

    // Admin Product Management (Vue + Inertia)
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.delete');

    // Admin Orders (product orders only)
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');

    // Admin dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('admin/AdminDashboard');
    })->name('admin.dashboard');

    // Admin blog CRUD
    Route::get('/blogs', [\App\Http\Controllers\BlogController::class, 'adminIndex'])->name('admin.blogs');
    Route::post('/blogs', [\App\Http\Controllers\BlogController::class, 'store'])->name('admin.blogs.store');
    Route::post('/blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'update'])->name('admin.blogs.update');
    Route::delete('/blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'destroy'])->name('admin.blogs.destroy');

    // Admin support/issues management (fetches all issues)
    Route::get('/support', [\App\Http\Controllers\SupportController::class, 'index'])->name('admin.support');
    Route::post('/support/{id}/status', [\App\Http\Controllers\SupportController::class, 'updateStatus'])->name('admin.support.status');

    // Admin Prescription Management
    Route::get('/prescriptions', [\App\Http\Controllers\Admin\PrescriptionController::class, 'index'])->name('admin.prescriptions');
    Route::get('/prescriptions/{id}', [\App\Http\Controllers\Admin\PrescriptionController::class, 'show'])->name('admin.prescriptions.show');
    Route::patch('/prescriptions/{id}/status', [\App\Http\Controllers\Admin\PrescriptionController::class, 'updateStatus'])->name('admin.prescriptions.status');

    // Admin Users Management
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users');
    Route::put('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.users.update');
});

// Include other route files
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// Admin Panel SPA Catch-all (MUST BE LAST)
Route::get('/admin/{any}', function () {
    return Inertia::render('admin/AdminDashboard');
})->where('any', '.*');
