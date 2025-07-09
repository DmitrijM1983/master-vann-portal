<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //Регистрация и авторизация
    Route::get('/registration', [AuthController::class, 'registrationForm'])->name('register_form');
    Route::post('/registration', [AuthController::class, 'registration'])->name('register');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login_form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    //Сброс пароля
    Route::get('/forgot-password', [AuthController::class, 'passwordRequest'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.email')
        ->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'passwordUpdate'])->name('password.update');
});




