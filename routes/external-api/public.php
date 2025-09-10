<?php 

use Illuminate\Support\Facades\Route;
use Modules\ModuleRelease2\Http\Controllers\Api\External\UserController;

// User data 
Route::get('/users', [UserController::class, 'index']);