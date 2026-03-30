<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/verify-otp', function () {
    return view('verify-otp');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ForgotPasswordController;
Route::get('/signup',  [AuthController::class, 'signupPage']);
Route::post('/signup', [AuthController::class, 'signupSubmit']);
Route::get('/signin',  [AuthController::class, 'signinPage']);
Route::get('/forget-password', [ForgotPasswordController::class, 'showPage']);
Route::post('/forget-password', [ForgotPasswordController::class, 'submit']);
Route::post('/signin', [AuthController::class, 'signinSubmit']);
Route::get('/captcha', [AuthController::class, 'generateCaptcha']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/logout',  [AuthController::class, 'logout']);
Route::get('/resend-otp', [AuthController::class, 'resendOtp']);
Route::get('/form', [FormController::class, 'index']);
Route::post('/submit', [FormController::class, 'submit']);