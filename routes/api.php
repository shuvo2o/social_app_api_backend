<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Middleware for getting authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::group(['prefix' => 'auth'], function() {
    Route::controller(\App\Http\Controllers\API\AuthController::class)->group(function() {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::get('get-profile', 'getProfile');
    });
});

// Routes that require authentication
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::controller(\App\Http\Controllers\API\AuthController::class)->group(function() {
        Route::get('logout', 'logout');

    });
});
