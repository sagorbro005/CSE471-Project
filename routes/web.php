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

// Include other route filesS
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
