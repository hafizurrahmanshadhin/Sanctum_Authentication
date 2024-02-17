<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Web API Routes
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/user-profile', [UserController::class, 'UserProfile'])->middleware('auth:sanctum');
Route::get('/logout', [UserController::class, 'UserLogout'])->middleware('auth:sanctum');
Route::post('/user-update', [UserController::class, 'UpdateProfile'])->middleware('auth:sanctum');
Route::post('/send-otp', [UserController::class, 'SendOTP']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware('auth:sanctum');

// Pages Routes
Route::view('/', 'pages.home');
Route::view('/userLogin', 'pages.auth.login-page')->name('login');
Route::view('/userRegistration', 'pages.auth.registration-page');
Route::view('/sendOtp', 'pages.auth.send-otp-page');
Route::view('/verifyOtp', 'pages.auth.verify-otp-page');
Route::view('/resetPassword', 'pages.auth.reset-pass-page');
Route::view('/userProfile', 'pages.dashboard.profile-page');
