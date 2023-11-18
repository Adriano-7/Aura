<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyEventController;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::controller(HomeController::class)->group(function () {
    Route::get('/',  'show')->name('home');
});

//Notifications
Route::controller(NotificationsController::class)->group(function () {
    Route::get('/notificacoes', 'show')->name('notifications');
});

//Events
Route::controller(EventController::class)->group(function () {
    Route::get('/eventos/{id}', 'show')->name('events');
});

//My Events
Route::controller(MyEventController::class)->group(function () {
    Route::get('/meus-eventos', 'show')->name('my-events');
});

// API

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
