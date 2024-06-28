<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Middleware for getting authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::group(['prefix' => 'auth'], function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::get('send-mail', 'testMail');
        Route::post('forget-password-request', 'forgetPasswordRequest');
        Route::post('forget-password', 'verifyAndChangePassword');
        
    });
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::controller(AuthController::class)->group(function() {
            Route::get('logout', 'logout');
            Route::get('get-profile', 'getProfile');
    
        });
    });
});

// Routes that require authentication

