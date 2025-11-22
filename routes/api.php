<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout']);
Route::get('/me',        [AuthController::class, 'me']);

Route::get('/locations/provinces', [LocationController::class, 'provinces']);
Route::get('/locations/cities/{province}', [LocationController::class, 'cities']);
Route::get('/locations/districts/{city}', [LocationController::class, 'districts']);
Route::get('/locations/villages/{district}', [LocationController::class, 'villages']);

