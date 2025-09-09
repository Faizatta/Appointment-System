<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PhoneVerificationController; // 👈 add this
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // --- Registration ---
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // --- Login ---
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // --- Forgot Password (EMAIL) ---
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // --- Reset Password (EMAIL) ---
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // --- Forgot Password (PHONE OTP) ---
    Route::get('forgot-password-phone', [PhoneVerificationController::class, 'showForm'])->name('password.request.phone');
    Route::post('forgot-password-phone', [PhoneVerificationController::class, 'send'])->name('verify.phone.post');

    // --- Verify Phone OTP ---
    Route::get('verify-phone', [PhoneVerificationController::class, 'showOTPForm'])->name('verify.phone');
    Route::post('verify-phone', [PhoneVerificationController::class, 'verify'])->name('verify.phone.verify');

    // --- Reset Password via Phone ---
    Route::get('reset-password-phone', [PhoneVerificationController::class, 'showResetForm'])->name('reset.password.phone');
    Route::post('reset-password-phone', [PhoneVerificationController::class, 'resetPassword'])->name('reset.password.phone.post');

    // --- Resend OTP ---
    Route::get('resend-otp', [PhoneVerificationController::class, 'resend'])->name('resend.otp');
});

Route::middleware('auth')->group(function () {
    // --- Email Verification ---
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // --- Confirm Password ---
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // --- Update Password ---
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // --- Logout ---
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
