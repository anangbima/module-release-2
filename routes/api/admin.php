<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleRelease2\Http\Controllers\Api\Internal\Admin\UserController;

/**
 * Wrap with prefix adn name 'admin'
 * Authentication 
 */
Route::prefix('admin')->middleware(module_release_2_meta('snake').'-auth')->group(function () {
    /**
     * Check role 
     */
    Route::middleware(module_release_2_meta('snake').'.role:'.module_release_2_meta('snake').'_web,admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
});