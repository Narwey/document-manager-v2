<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureAdmin;

// Auth routes
Route::post('/register' , [AuthController::class, 'register']);
Route::post('/login' , [AuthController::class, 'login']);
Route::post('/logout' , [AuthController::class, 'logout'])->middleware('auth:sanctum');


// admin routes
Route::apiResource('users', UserController::class)
    ->middleware(['auth:sanctum', EnsureAdmin::class]);