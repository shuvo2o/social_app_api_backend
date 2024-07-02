<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;

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
            Route::post('change-password', 'changePassword');
            Route::post('update-profile', 'updateProfile');
    
        });
    });
});

Route::group(['middleware'=> 'auth:sanctum'], function(){
    Route::group(['prefix'=>'user'], function(){
        Route::apiResource('posts', PostController::class);
    });
});



