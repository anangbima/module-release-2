<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\AuthenticatedSessionController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\ChangePasswordController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\ConfirmablePasswordController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\EmailVerificationNotificationController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\EmailVerificationPromptController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\NewPasswordController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\OtpVerificationController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\PasswordResetLinkController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\RegisteredUserController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\SocialLoginController;
use Modules\ModuleRelease2\Http\Controllers\Web\Auth\VerifyEmailController;

/**
 * Wrap route without authentication needed with middleware guest
 * 
 * 1. Register - ✅✅
 * 2. Login - ✅✅
 * 3. Forgot Password - ✅✅
 * 4. Reset Password - ✅✅
 * 5. Verify OTP - ✅✅
 * 6. Socialite - ⛔
 * 
 */
Route::middleware(module_release_2_meta('snake').'.guest')->group(function () {
    // Register
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    // Verify OTP
    Route::get('verify-otp', [OtpVerificationController::class, 'create'])->name('verify-otp');
    Route::post('verify-otp', [OtpVerificationController::class, 'store']);
    Route::post('verify-otp/resend', [OtpVerificationController::class, 'resend'])->name('verify-otp.resend');

    // Socialite
    // Route::get('login/{provider}', [SocialLoginController::class, 'redirectToProvider'])->name('social.login');
    // Route::get('login/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback'])->name('social.callback');
});

/**
 * Wrap route taht need authentication with middleware auth
 * 
 * 1. Verify Email - ✅✅
 * 2. Confirmable Password - ✅✅
 * 3. Change Password - ✅✅
 * 4. Logout - ✅✅
 * 
 */
Route::middleware(module_release_2_meta('snake').'-auth')->group(function () {
    // Verifiy email 
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{user}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    // Confirmable password
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'create'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Change password
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'store'])->middleware('throttle:6,1')->name('password.store');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

