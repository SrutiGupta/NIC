<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
Route::get('/signup',  [AuthController::class, 'signupPage']);
Route::post('/signup', [AuthController::class, 'signupSubmit']);
Route::get('/signin',  [AuthController::class, 'signinPage']);
Route::post('/signin', [AuthController::class, 'signinSubmit']);
Route::get('/captcha', [AuthController::class, 'generateCaptcha']);
Route::get('/logout',  [AuthController::class, 'logout']);
Route::get('/form', [FormController::class, 'index']);
Route::post('/submit', [FormController::class, 'submit']);