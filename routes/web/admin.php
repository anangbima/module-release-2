<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\ApiClientController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\ApiClientStatusController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\HomeController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\LogActivityController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\LogActivityRoleController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\LogActivityUserController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\NotificationController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\PermissionController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\ProfileController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\ProfileImageController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\RoleController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\SettingController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\UserController;
use Modules\ModuleRelease2\Http\Controllers\Web\Admin\UserStatusController;

/**
 * Wrap with prefix adn name 'admin'
 * Authentication 
 */
Route::prefix('admin')->middleware(module_release_2_meta('snake') . '-auth')->group(function () {
    /**
     * Check role 
     */
    Route::middleware(module_release_2_meta('snake') . '.role:' . module_release_2_meta('snake') . '_web,admin')->name('admin.')->group(function () {
        // Index
        Route::get('/', [HomeController::class, 'index'])->name('index');

        // Profile
        Route::resource('profile', ProfileController::class)
            ->names('profile')
            ->parameter('profile', 'user')
            ->only([
                'index',
                'update'
            ]);

        // Profile Image
        Route::group([
            'prefix' => 'profile-image',
            'as' => 'profile-image.',
        ], function () {
            Route::put('/', [ProfileImageController::class, 'update'])->name('update');
            Route::delete('/', [ProfileImageController::class, 'destroy'])->name('destroy');
        });

        // Users
        Route::get('users/export/{type}', [UserController::class, 'export'])->name('users.export');
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::resource('users', UserController::class)
            ->names('users')
            ->parameter('users', 'user')
            ->only([
                'index',
                'create',
                'store',
                'edit',
                'update',
                'destroy',
                'show'
            ]);
        // Export Users

        // User Status
        Route::prefix('users/{user}/status')->name('users.status.')->group(function () {
            Route::put('/', [UserStatusController::class, 'update'])->name('update');
        });

        // Roles
        Route::resource('roles', RoleController::class)
            ->names('roles')
            ->parameter('roles', 'role')
            ->only([
                'index',
                'create',
                'store',
                'edit',
                'update',
                'destroy',
                'show'
            ]);

        // Permissions
        Route::resource('permissions', PermissionController::class)
            ->names('permissions')
            ->parameter('permissions', 'permission')
            ->only([
                'index',
                'create',
                'store',
                'edit',
                'update',
                'destroy',
                'show'
            ]);

        // Log By Role
        Route::get('logs/by-role/{role?}', [LogActivityRoleController::class, 'index'])
            ->name('logs.by-role');

        // User Log Activities
        Route::get('logs/user', [LogActivityUserController::class, 'index'])
            ->name('logs.user');

        // Log Activities
        Route::get('logs/export/{type}', [LogActivityController::class, 'export'])->name('logs.export');
        Route::resource('logs', LogActivityController::class)
            ->names('logs')
            ->parameter('logs', 'log')
            ->only([
                'index',
                'show'
            ]);

        // API Clients
        Route::resource('api-clients', ApiClientController::class)
            ->names('api-clients')
            ->parameter('api-clients', 'api_client')
            ->only([
                'index',
                'create',
                'store',
                'destroy',
                'show'
            ]);
        
        // API Client Status
        Route::prefix('api-clients/{api_client}/status')->name('api-clients.status.')->group(function () {
            Route::put('/', [ApiClientStatusController::class, 'update'])->name('update');
        });

        // Notifications
        Route::group([
            'prefix' => 'notifications',
            'as' => 'notifications.',
        ], function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
            Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
            Route::post('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });
    });
});
