<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'verified']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:otp:verify'])->group(function () {
    Route::post('/otp/resend', [AuthController::class, 'resendOtp']);
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);
    });
    
    Route::apiResource('categories', CategoryController::class)->middleware(['auth:sanctum', 'isAdmin']);