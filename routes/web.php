<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'signinview')->name('login');
    Route::post('/login', 'authenticate')->name('loginProses');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');


});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/adminDash', 'index')->name('adminDash');
    });
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('/categoryAdmin', 'index')->name('categoryAdmin');
        Route::get('/categoryAdmin/create', 'create')->name('categoryAdmin.create'); // Form tambah kategori
        Route::post('/categoryAdmin', 'store')->name('categoryStore'); // Simpan kategori baru
        Route::get('/categoryAdmin/{category}/edit', 'edit')->name('categoryEdit'); // Form edit kategori
        Route::put('/categoryAdmin/{category}', 'update')->name('categoryAdmin.update'); // Update kategori
        Route::delete('/categoryAdmin/{category}', 'destroy')->name('categoryDestroy'); // Hapus kategori
        Route::post('/admin/categories/set-default',  'setDefaultForUsers')->name('category.setDefaultForUsers');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users');
        Route::post('/users/store', 'store')->name('userStore');
        Route::get('/users/edit/{id}', 'edit')->name('usersEdit');
        Route::post('/users/update/{id}', 'update')->name('usersUpdate');
        Route::delete('/users/delete/{id}', 'destroy')->name('usersDelete');
    });
});

Route::middleware(['auth', 'role:users'])->group(function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('/taskMenu', 'index')->name('task.index'); // Menggunakan nama route 'task.index'
        Route::post('/createTask', 'store')->name('createTask');
        Route::get('/editTask/{task}', 'edit')->name('editTask');
        Route::post('/updateTask/{id}', 'update')->name('updateTask');
        Route::delete('/deleteTask/{task}', 'destroy')->name('deleteTask');
        Route::patch('/tasks/{task}/toggle', 'toggleComplete')->name('task.toggleComplete'); // Mengubah status selesai/belum selesai
        Route::post('/getTasksByDate', 'getTasksByDate')->name('getTasksByDate'); // Mendapatkan tugas berdasarkan tanggal
        Route::post('/updateTaskStatus/{id}', 'updateStatus')->name('updateTaskStatus');
        Route::post('/tasks/update-priority/{id}', 'updatePriority')->name('updatePriority');

    });

    Route::controller(ChartController::class)->group(function () {
        Route::get('/charts', 'taskSummary')->name('charts');
    });

    Route::controller(CalendarController::class)->group(function () {
        Route::get('/calendar', 'index')->name('calendar');
        Route::post('/calendar', 'store')->name('calendar.store'); // Menambahkan event baru
        Route::put('/calendar/{id}', 'update')->name('calendar.update'); // Mengupdate event
        Route::delete('/calendar/{id}', 'destroy')->name('calendar.destroy'); // Menghapus event
    });
});
