<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
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

Route::middleware(['auth', 'role:users'])->group(function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('/taskMenu', 'index')->name('taskMenu');
        Route::post('/createTask', 'store')->name('createTask');
        Route::get('/editTask/{task}', 'edit')->name('editTask');
        Route::post('/updateTask/{task}', 'update')->name('updateTask');
        Route::delete('/deleteTask/{task}', 'destroy')->name('deleteTask');
});

});

