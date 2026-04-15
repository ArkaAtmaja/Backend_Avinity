<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::apiResource('menu', MenuController::class);

