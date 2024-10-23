<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
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

Route::apiResource('categories', CategoryController::class)
    ->middleware(['auth:sanctum', EnsureAdmin::class]);



    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::get('/documents/category/{id}', [DocumentController::class, 'getByCategory']);
    Route::delete('/documents/{id}', [DocumentController::class, 'delete']);

