<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Middleware\TokenAuth;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);

Route::get('/internships', [InternshipController::class, 'index']);
Route::get('/internships/{internship}', [InternshipController::class, 'show']);

Route::middleware(TokenAuth::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/change-password', [ProfileController::class, 'changePassword']);

    Route::post('/companies', [CompanyController::class, 'store']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);

    Route::post('/internships', [InternshipController::class, 'store']);
    Route::put('/internships/{internship}', [InternshipController::class, 'update']);
    Route::delete('/internships/{internship}', [InternshipController::class, 'destroy']);

    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks/{internship}', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{internship}', [BookmarkController::class, 'destroy']);
});