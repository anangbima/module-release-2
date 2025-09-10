<?php 

use Illuminate\Support\Facades\Route;
use Modules\DesaModuleTemplate\Http\Controllers\Api\External\UserController;

// User data 
Route::get('/users', [UserController::class, 'index']);