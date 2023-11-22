<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CreateEventController;
use App\Http\Controllers\MyEventsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\ReportEventController;

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
    Route::get('/dashboard/denuncias', [DashboardController::class, 'showReports'])->name('dashboard.reports');
    Route::get('/dashboard/membros', [DashboardController::class, 'showMembers'])->name('dashboard.members');
    Route::get('/dashboard/organizacoes', [DashboardController::class, 'showOrganizations'])->name('dashboard.organizations');
});

//Notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notificacoes', [NotificationsController::class, 'show'])->name('notifications');

    Route::delete('/notificacoes/{id}/apagar', [NotificationsController::class, 'delete'])->name('notification.delete');
    Route::get('/notificacoes/{id}/marcar-como-vista', [NotificationsController::class, 'markAsSeen'])->name('notification.markAsSeen');
    Route::patch('/notificacoes/{id}/aceitar-convite', [NotificationsController::class, 'acceptInvitation'])->name('notification.acceptInvitation');

    Route::middleware(['admin'])->group(function () {
        Route::patch('/notificacoes/{id}/aprovar-organizacao', [NotificationsController::class, 'approveOrganization'])->name('notification.approveOrganization');
    });
});

//Events
Route::controller(EventController::class)->group(function () {
    Route::get('/evento/{id}', 'show')->name('event');
    Route::get('api/evento/{id}/aderir', 'joinEvent')->name('event.join');
    Route::get('api/evento/{id}/sair', 'leaveEvent')->name('event.leave');
    Route::get('/evento/{id}/aderir', 'joinEvent')->name('event.join');
    Route::delete('/evento/{id}/apagar', 'destroy')->name('event.delete');

    //Api
    Route::get('/api/eventos/pesquisa', 'search')->name('events.search');
    Route::delete('api/event/{id}', 'ApiDelete'); // refactor later
});

//My Events
Route::middleware(['auth'])->group(function () {
    Route::get('/meus-eventos', [MyEventsController::class, 'participating'])->name('my-events');
    Route::get('/filter-events/participating', [MyEventsController::class, 'participating'])->name('participating');
    Route::get('/filter-events/organizing', [MyEventsController::class, 'organizing'])->name('organizing');

});

//Create Events
Route::middleware(['auth'])->group(function () {
    Route::get('/criar-evento', [CreateEventController::class, 'show'])->name('criar-evento');
    Route::post('/submit-event', [CreateEventController::class, 'store']) ->name('submit-event');
});

//Organization
Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organizacao/{id}', 'show')->name('organization.show');
    Route::get('/organizacao/{id}/aderir', 'joinOrganization')->name('organization.join');
    Route::post('/organizacao/convidar-utilizador', 'inviteUser')->name('organization.inviteUser');
    Route::post('organizacao/remover-utilizador', 'eliminateMember')->name('organization.eliminateMember');

    // refactor later
    Route::middleware(['admin'])->group(function () {
        Route::delete('api/organization/{id}', 'ApiDelete');
    });
});

//Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/pesquisa', 'show')->name('search');
}); 

// API
Route::controller(CommentController::class)->group(function () {
    Route::get('api/comments', 'index');
    Route::get('api/comments/{id}', 'show');
    Route::delete('api/comments/{id}', 'destroy')->name('comment.delete');
    Route::post('comments/add', 'store')->name('comment.add');
});

Route::controller(ReportEventController::class)->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('api/reports/event', 'index');
        Route::patch('api/reports/event/{id}/resolved', 'markAsResolved');
    });
});

Route::controller(ReportCommentController::class)->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('api/reports/comment', 'index');
        Route::patch('api/reports/comment/{id}/resolved', 'markAsResolved');
    });
});

Route::controller(UserController::class)->group(function() {
    Route::middleware(['admin'])->group(function () {
        Route::delete('api/user/{id}', 'destroy');
    });
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
