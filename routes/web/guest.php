<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleRelease2\Http\Controllers\Web\Guest\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('index');