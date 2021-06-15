<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use  App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::post('register', [UserController::class, 'register'])->name('register');

Route::post('login', [UserController::class, 'login'])->name('login');

Route::get('user', [UserController::class, 'user'])->name('user');
