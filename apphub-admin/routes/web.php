<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\MFAController;
// use App\Http\Controllers\Auth\AzureController; // SSO dinonaktifkan
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteTypeController;
use App\Http\Controllers\BusinessCategoryController;


// =============================
// Login SSO Microsoft (Azure AD): dinonaktifkan, aktifkan kembali bila diperlukan
// =============================
// Route::get('/login/azure', [AzureController::class, 'redirectToAzure'])->name('login.azure');
// Route::get('/login/azure/callback', [AzureController::class, 'handleAzureCallback']);


// =============================
// Default Route "/" => Redirect jika sudah login
// =============================
Route::get('/', function () {
    $rememberCookieName = Auth::getRecallerName(); // cookie name for remember me
    $hasRememberMe = request()->hasCookie($rememberCookieName); // apakah ada cookie remember me

    if (auth()->check()) {
        // Jika login dengan remember me, redirect home
        if ($hasRememberMe) {
            return redirect()->route('home');
        }

        // Jika login biasa (tanpa remember me), cek apakah session aktif
        // Jika session aktif, redirect home
        // Jika session tidak aktif (expire), akan otomatis kembali ke login oleh middleware 'auth'
        return redirect()->route('home');
    }

    // Jika tidak login, tampilkan halaman login
    return view('auth.login');
})->name('login');

// ============================================
// Routes untuk user yang sudah login tapi BELUM verified atau BELUM MFA
// ============================================
Route::middleware(['auth'])->group(function () {

    // ========== Verifikasi Email ==========
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('verified')->name('verification.notice');

    // ========== MFA Setup & Verify ==========
    Route::middleware(['verified'])->group(function () {
        Route::get('/mfa/setup', [MFAController::class, 'showSetup'])->name('mfa.setup');
        Route::get('/mfa/verify', [MFAController::class, 'showVerify'])->name('mfa.verify');
        Route::post('/mfa/verify', [MFAController::class, 'verify'])->name('mfa.verify.post');
    });

});

// ============================================
// Routes untuk user yang sudah login + verified email + verified MFA
// ============================================
Route::middleware(['auth', 'verified', 'mfa'])->group(function () {

    // ========== Home / Dashboard ==========
    Route::get('/home', function () {
        return view('layouts.home');
    })->name('home');

    // ========== User Management ==========
    Route::prefix('user')->middleware('role:superadmin|admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/search', [UserController::class, 'index'])->name('user.search');
        Route::get('/create', [UserController::class, 'index'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });

        // ========== Website Type Management ==========
    Route::prefix('website-type')->middleware('role:superadmin|admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\WebsiteTypeController::class, 'index'])->name('website-type');
        Route::post('/store', [\App\Http\Controllers\WebsiteTypeController::class, 'store'])->name('website-type.store');
        Route::put('/{id}/update', [\App\Http\Controllers\WebsiteTypeController::class, 'update'])->name('website-type.update');
        Route::delete('/{id}/delete', [\App\Http\Controllers\WebsiteTypeController::class, 'destroy'])->name('website-type.destroy');
    });

    // ========== Business Category Management ==========
    Route::prefix('business-categories')->middleware('role:superadmin|admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\BusinessCategoryController::class, 'index'])->name('business-categories');
        Route::post('/store', [\App\Http\Controllers\BusinessCategoryController::class, 'store'])->name('business-categories.store');
        Route::put('/{id}/update', [\App\Http\Controllers\BusinessCategoryController::class, 'update'])->name('business-categories.update');
        Route::delete('/{id}/delete', [\App\Http\Controllers\BusinessCategoryController::class, 'destroy'])->name('business-categories.destroy');
    });

    // ========== Role Management ==========
    Route::prefix('role')->middleware('role:superadmin')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role');
        Route::get('/create', [RoleController::class, 'create'])->name('role.create');
        Route::get('/filter', [RoleController::class, 'filter'])->name('role.filter');
        Route::get('/search', [RoleController::class, 'search'])->name('role.search');
        Route::post('/', [RoleController::class, 'store'])->name('role.store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('role.edit')->where('id', '[0-9]+');
        Route::put('/{id}', [RoleController::class, 'update'])->name('role.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('role.destroy')->where('id', '[0-9]+');
    });

    // ========== List Management ==========
    Route::prefix('list')->middleware('role:superadmin|admin')->group(function () {
        Route::get('/', [ListController::class, 'index'])->name('list');
        Route::put('/{id}/toggle-status', [ListController::class, 'toggleStatus'])->name('list.toggleStatus');
        Route::post('/store', [ListController::class, 'store'])->name('list.store');
        Route::put('/{id}/update', [ListController::class, 'update'])->name('list.update');
        Route::delete('/{id}/delete', [ListController::class, 'delete'])->name('list.delete');
    });

    // ========== Profile Management ==========
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ========== Password Management ==========
    Route::post('/password/change', [PasswordController::class, 'changePassword'])->name('password.change');
    Route::get('/password/check-expiry', [PasswordController::class, 'checkPasswordExpiry'])->name('password.expiry');

    // ========== Logout ==========
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ============================================
// Include route login/register bawaan Laravel (auth scaffolding default Laravel Breeze/Fortify/etc.)
// ============================================
require __DIR__.'/auth.php';