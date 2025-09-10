<?php

use Illuminate\Support\Facades\Route;
use Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Admin\UserController;

/**
 * Wrap with prefix adn name 'admin'
 * Authentication 
 */
Route::prefix('admin')->middleware(desa_module_template_meta('snake').'-auth')->group(function () {
    /**
     * Check role 
     */
    Route::middleware(desa_module_template_meta('snake').'.role:'.desa_module_template_meta('snake').'_web,admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
});