<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DashboardController;

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

    Route::delete('/notificacoes/{id}/apagar', [NotificationsController::class, 'delete'])->name('notification.delete');
    Route::get('/notificacoes/{id}/marcar-como-vista', [NotificationsController::class, 'markAsSeen'])->name('notification.markAsSeen');
    Route::patch('/notificacoes/{id}/aceitar-convite', [NotificationsController::class, 'acceptInvitation'])->name('notification.acceptInvitation');
});

//Events
Route::controller(EventController::class)->group(function () {
    Route::get('/eventos/{id}', 'show')->name('events');
    Route::get('api/evento/{id}/aderir', 'joinEvent')->name('event.join');
    Route::get('api/evento/{id}/sair', 'leaveEvent')->name('event.leave');
});


//My Events
Route::middleware(['auth'])->group(function () {
    Route::get('/meus-eventos', [MyEventsController::class, 'show'])->name('my-events');
});

//Organization
Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organizacao/{id}', 'show')->name('organization');
    Route::get('/organizacao/{id}/aderir', 'joinOrganization')->name('organization.join');
});

// API
Route::controller(CommentController::class)->group(function () {
    Route::get('api/comments', 'index');
    Route::get('api/comments/{id}', 'show');
    Route::delete('api/comments/{id}', 'destroy')->name('comment.delete');
    Route::post('comments/add', 'store')->name('comment.add');
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
