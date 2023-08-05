<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReadController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [UserController::class, 'user']);

    Route::post('books', [BookController::class, 'store']);
    Route::get('books', [BookController::class, 'index']);
    Route::put('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);
    Route::get('books/{id}', [BookController::class, 'show']);

    Route::post('books/{book_id}/read', [ReadController::class, 'store']);
    Route::put('books/{book_id}/read', [ReadController::class, 'updateLastRead']);
    Route::delete('books/{book_id}/read', [ReadController::class, 'destroyLastRead']);
    Route::post('books/{book_id}/read/finish', [ReadController::class, 'finishRead']);
});


Route::post('register', [RegisterController::class, 'store'], ['middleware' => 'auth:guest']);
Route::post('resend-email', [RegisterController::class, 'resendEmail'], ['middleware' => 'auth:guest']);
Route::post('verify-email', [RegisterController::class, 'verifyEmail'], ['middleware' => 'auth:guest']);
Route::post('login', [AuthController::class, 'login'], ['middleware' => 'auth:guest']);