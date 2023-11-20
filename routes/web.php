<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;

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

//Dashboard
Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
});

//Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notificacoes', [NotificationsController::class, 'show'])->name('notifications');
});

//Events
Route::controller(EventController::class)->group(function () {
    Route::get('/eventos/{id}', 'show')->name('events');
});

//My Events
Route::middleware(['auth'])->group(function () {
    Route::get('/meus-eventos', [MyEventsController::class, 'show'])->name('my-events');
});

//Organization
Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organizacao/{id}', 'show')->name('organization');
});

//Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/pesquisa', 'show')->name('search');
}); 

// API
Route::controller(CommentController::class)->group(function () {
    Route::get('api/comments', 'index');
    Route::get('api/comments/{id}', 'show');
    Route::delete('api/comments/{id}', 'destroy');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/iniciar-sessao', 'showLoginForm')->name('login');
    Route::post('/iniciar-sessao', 'authenticate');
    Route::get('/terminar-sessao', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/registar', 'showRegistrationForm')->name('register');
    Route::post('/registar', 'register');
});
