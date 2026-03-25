<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/form', function () {
    return view('form');
});

use App\Http\Controllers\AuthController;

Route::get('/signup',  [AuthController::class, 'signupPage']);
Route::post('/signup', [AuthController::class, 'signupSubmit']);
Route::get('/signin',  [AuthController::class, 'signinPage']);
Route::post('/signin', [AuthController::class, 'signinSubmit']);
Route::get('/logout',  [AuthController::class, 'logout']);
Route::post('/submit', [FormController::class, 'submit']);