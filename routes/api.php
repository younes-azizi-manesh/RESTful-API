<?php

use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('v1/posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/store', [PostController::class, 'store']);
        Route::get('/show/{post}', [PostController::class, 'show']);
        Route::put('/update/{post}', [PostController::class, 'update']);
        Route::delete('/destroy/{post}', [PostController::class, 'destroy']);
    });
});

Route::middleware('guest:sanctum')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
