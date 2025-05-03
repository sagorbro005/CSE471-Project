<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PrescriptionController;

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

// Support page
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support', [SupportController::class, 'store'])->name('support.store');

// Blogs (public)
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');

// Prescription upload
Route::get('/upload-prescription', [PrescriptionController::class, 'index'])->name('prescription.index');
Route::post('/prescription/upload', [PrescriptionController::class, 'upload'])->name('prescription.upload');
Route::get('/prescription/view', [PrescriptionController::class, 'view'])->name('prescription.view');

// -------------------- ADMIN ROUTES --------------------
Route::prefix('admin')->group(function () {
    // Admin login (handled by Vue, so just a placeholder route)
    Route::get('/login', function () {
        return Inertia::render('admin/AdminLogin');
    })->name('admin.login');

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
});

// Include other route files
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
