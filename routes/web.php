<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\MentorVerificationController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AdminBankAccountController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OTP verification routes
Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/request', [OtpController::class, 'requestOtp'])->name('otp.request');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// Forgot & Reset Password
Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.forgot');
Route::post('/password/email', [PasswordResetController::class, 'sendLink'])->name('password.email');
Route::get('/reset-password', [PasswordResetController::class, 'showReset'])->name('password.reset.form');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');

// Admin routes
Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profil admin
        Route::get('/profile', [AdminUserController::class, 'profile'])->name('profile');

        // Kategori & Tag
        Route::resource('categories', AdminCategoryController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('tags', AdminTagController::class)->only(['index','create','store','edit','update','destroy']);

        // Toggle aktif/non-aktif
        Route::patch('categories/{category}/toggle', [AdminCategoryController::class, 'toggle'])->name('categories.toggle');
        Route::patch('tags/{tag}/toggle', [AdminTagController::class, 'toggle'])->name('tags.toggle');

        // Verifikasi Mentor
        Route::get('mentor-verifications', [MentorVerificationController::class, 'index'])->name('mentor_verifications.index');
        Route::post('mentor-verifications/{verification}/approve', [MentorVerificationController::class, 'approve'])->name('mentor_verifications.approve');
        Route::post('mentor-verifications/{verification}/reject', [MentorVerificationController::class, 'reject'])->name('mentor_verifications.reject');

        // Transaksi - verifikasi pembayaran
        Route::get('transactions/pending', [AdminTransactionController::class, 'pending'])->name('transactions.pending');
        Route::post('transactions/{transaction}/verify', [AdminTransactionController::class, 'verify'])->name('transactions.verify');

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class)->only(['index','store','destroy']);

        // Rekening bank admin
        Route::resource('admin-bank-accounts', AdminBankAccountController::class)->only(['index','store','destroy']);

        // Kelola User
        Route::resource('users', AdminUserController::class)->only(['index','create','store','show','edit','update','destroy']);

        // Settings
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    });
