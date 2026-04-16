<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

//Menu
Route::get('/menu/search', [MenuController::class, 'search']);
Route::get('/menu', [MenuController::class, 'index']);
Route::post('/menu', [MenuController::class, 'store']);
Route::put('/menu/{id}', [MenuController::class, 'update']);
Route::delete('/menu/{id}', [MenuController::class, 'destroy']);
