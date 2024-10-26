<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Statistique;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureAdmin;

// Auth routes
    Route::post('/register' , [AuthController::class, 'register']);
    Route::post('/login' , [AuthController::class, 'login']);
    Route::post('/logout' , [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/stat' , [Statistique::class , 'statistic']);

// admin routes
    Route::apiResource('users', UserController::class)
    ->middleware(['auth:sanctum', EnsureAdmin::class]);
// admin and manager routes
    Route::apiResource('categories', CategoryController::class)
    ->middleware(['auth:sanctum', EnsureAdmin::class]);

    Route::get('/documents/categories' , [CategoryController::class , 'index'])->middleware('auth:sanctum');
    Route::get('/documents/user', [DocumentController::class, 'getUserDocuments'])->middleware('auth:sanctum');


    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->middleware('auth:sanctum');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->middleware('auth:sanctum');
    Route::get('/documents/category/{id}', [DocumentController::class, 'getByCategory'])->middleware('auth:sanctum');
    Route::delete('/documents/{id}', [DocumentController::class, 'delete'])->middleware('auth:sanctum');

