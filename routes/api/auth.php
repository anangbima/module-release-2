<?php

use Illuminate\Support\Facades\Route;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\AuthenticatedSessionController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\ChangePasswordController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\ConfirmablePasswordController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\EmailVerificationNotificationController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\NewPasswordController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\PasswordResetLinkController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\RegisteredUserController;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth\VerifyEmailController;

/**
 * Wrap route without authentication needed with middleware guest
 * 
 * 1. Register - ✅
 * 2. Login - ✅
 * 3. Forgot Password - 
 * 4. Reset Password - 
 * 
 */
Route::middleware(desa_module_template_meta('snake').'.guest')->group(function () {
    // Register
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register');

    // Login
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');

    // Forgot password
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password
    Route::post('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

/**
 * Wrap route that need authentication with middleware auth
 * 
 * 1. Verify Email - 
 * 2. Confirmable Password - ✅
 * 3. Change Password - ✅
 * 4. Logout - ✅
 * 
 */
Route::get('verify-email/{user}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::middleware(['auth:'.desa_module_template_meta('snake').'_api'])->group(function () {
    
    // Verify email
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    // Confirmable password
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Change password
    Route::post('change-password', [ChangePasswordController::class, 'store'])->middleware('throttle:6,1')->name('password.store');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});