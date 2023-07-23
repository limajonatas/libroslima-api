<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::post('register', [RegisterController::class, 'store']);
Route::post('verify-email', [RegisterController::class, 'verifyEmail']);
Route::post('login', [AuthController::class, 'login']);
