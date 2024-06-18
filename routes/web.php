<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsTwilioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'create'])->name('register.store');
Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('verify');
Route::post('/verify', [AuthController::class, 'verify'])->name('verify.check');
Route::get('/Home', function () { return view('Home');})->name('Home');;

