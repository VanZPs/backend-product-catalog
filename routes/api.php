<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerOnboardingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductPublicController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->middleware('api.auth');
Route::get('/me',        [AuthController::class, 'me'])->middleware('api.auth');

/*
|--------------------------------------------------------------------------
| SELLER ONBOARDING
|--------------------------------------------------------------------------
*/
Route::post('/seller/onboard', [SellerOnboardingController::class, 'store'])
    ->middleware('api.auth');

/*
|--------------------------------------------------------------------------
| SELLER PRODUCTS
|--------------------------------------------------------------------------
*/
Route::middleware('api.auth')->prefix('seller')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::post('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| LOCATIONS
|--------------------------------------------------------------------------
*/
Route::prefix('locations')->group(function () {
    Route::get('/provinces',                [LocationController::class, 'provinces']);
    Route::get('/cities/{province}',        [LocationController::class, 'cities']);
    Route::get('/districts/{city}',         [LocationController::class, 'districts']);
    Route::get('/villages/{district}',      [LocationController::class, 'villages']);
});


/*
|--------------------------------------------------------------------------
| PUBLIC PRODUCTS
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductPublicController::class, 'index']);
Route::get('/products/search', [ProductPublicController::class, 'search']);
Route::get('/products/{slug}', [ProductPublicController::class, 'show']);
