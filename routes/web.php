<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;




Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'signinview')->name('login');
    Route::post('/login', 'authenticate')->name('loginProses');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/adminDash', 'index')->name('adminDash');
});
});
