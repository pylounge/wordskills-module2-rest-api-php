<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use  App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Models\User;

Route::post('register', [UserController::class, 'register'])->name('register');

Route::post('login', [UserController::class, 'login'])->name('login');

Route::get('user', [UserController::class, 'user'])->name('user');

Route::get('airport', [AirportController::class, 'airport'])->name('airport');

Route::get('flight', [FlightController::class, 'flight'])->name('flight');

Route::post('booking', [BookingController::class, 'booking'])->name('booking');

Route::get('booking/{code}', [BookingController::class, 'getBookingInfo'])->name('booking');

Route::get('booking/{code}/seat', [BookingController::class, 'getОccupiedSeat'])->name('booking');

Route::patch('booking/{code}/seat', [BookingController::class, 'choiceSeat'])->name('booking');
