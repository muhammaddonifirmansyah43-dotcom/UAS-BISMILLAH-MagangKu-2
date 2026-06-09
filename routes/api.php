<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TokenAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);

Route::get('/internships', [InternshipController::class, 'index']);
Route::get('/internships/{internship}', [InternshipController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(TokenAuth::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/change-password', [ProfileController::class, 'changePassword']);

    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks/{internship}', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{internship}', [BookmarkController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware([TokenAuth::class, AdminMiddleware::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword']);

    Route::post('/admin/companies', [CompanyController::class, 'store']);
    Route::put('/admin/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/admin/companies/{company}', [CompanyController::class, 'destroy']);

    Route::post('/admin/internships', [InternshipController::class, 'store']);
    Route::put('/admin/internships/{internship}', [InternshipController::class, 'update']);
    Route::delete('/admin/internships/{internship}', [InternshipController::class, 'destroy']);

    Route::patch('/admin/internships/{internship}/close', [InternshipController::class, 'close']);
    Route::patch('/admin/internships/{internship}/open', [InternshipController::class, 'open']);
});