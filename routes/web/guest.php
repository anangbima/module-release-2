<?php

use Illuminate\Support\Facades\Route;
use Modules\DesaModuleTemplate\Http\Controllers\Web\Guest\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('index');