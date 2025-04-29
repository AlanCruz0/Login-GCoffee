<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('verify-email', [AuthController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('verify-email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');
