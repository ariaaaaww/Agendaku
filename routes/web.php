<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCouncilController;
use App\Http\Controllers\TeachersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::get('/account', [AuthController::class, 'account'])->name('account');

Route::get('/students', [StudentController::class, 'student'])->name('index');

Route::get('/teachers', [TeachersController::class, 'teachers'])->name('teachers');

Route::get('/admin', [AdminController::class, 'admin'])->name('admin');

Route::get('/council', [StudentCouncilController::class, 'council'])->name('council');
