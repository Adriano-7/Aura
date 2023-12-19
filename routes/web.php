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
use App\Http\Controllers\EditEventController;
use App\Http\Controllers\RecoverPasswordController;

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

//Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/iniciar-sessao', 'showLoginForm')->name('login');
    Route::post('/iniciar-sessao', 'authenticate');

    Route::get('/terminar-sessao', 'logout')->name('logout');
});

// Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/registar', 'showRegistrationForm')->name('register');
    Route::post('/registar', 'register');
});

Route::controller(RecoverPasswordController::class)->group(function () {
    Route::get('/recuperar-password', 'showRecoverPasswordForm')->name('recoverPassword');
    Route::post('/recuperar-password', 'post');

    Route::get('/recuperar-password/{token}', 'showResetPasswordForm')->name('resetPassword');
    Route::post('/recuperar-password/{token}', 'reset')->name('resetPasswordPost');
});

// User
Route::controller(UserController::class)->group(function() {
    Route::get('/utilizador/{username}', 'show')->name('user');
    Route::delete('/api/utilizador/{id}/apagar', 'destroy');
});

// Dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/denuncias', 'showReports')->name('dashboard.reports');
    Route::get('/dashboard/membros', 'showUsers')->name('dashboard.members');
    Route::get('/dashboard/organizacoes', 'showOrganizations')->name('dashboard.organizations');
});

// Home
Route::controller(HomeController::class)->group(function () {
    Route::get('/',  'show')->name('home');
});

// Notifications
Route::controller(NotificationsController::class)->group(function () {
    Route::get('/notificacoes', 'show')->name('notifications');
    Route::put('/notificacoes/{id}/marcar-como-vista', 'markAsSeen')->name('notification.markAsSeen');

    Route::delete('/api/notificacoes/{id}/apagar', 'delete')->name('notification.delete');
}); 

// Comment Reports
Route::controller(ReportCommentController::class)->group(function () {
    Route::get('/api/denuncias/comentarios', 'index');
    Route::put('/api/denuncias/comentarios/{id}/marcar-resolvido', 'markAsResolved');
    Route::post('/api/denuncias/comentarios/{id}/reportar', 'report')->name('comment.report');
});

// Event Reports
Route::controller(ReportEventController::class)->group(function () {
    Route::get('/api/denuncias/evento', 'index');
    Route::put('/api/denuncias/evento/{id}/marcar-resolvido', 'markAsResolved');
    Route::post('/api/denuncias/evento/{id}/reportar', 'report')->name('event.report');
});

// My Events
Route::controller(MyEventsController::class)->group(function () {
    Route::get('/meus-eventos', 'show')->name('my-events');
});

// Create Events
Route::controller(CreateEventController::class)->group(function () {
    Route::get('/criar-evento','show')->name('criar-evento');
    Route::post('/submeter-evento','store') ->name('submit-event');
});

// Edit Events
Route::controller(EditEventController::class)->group(function () {
    Route::get('/editar-evento/{id}', 'show')->name('edit-event');
    Route::put('/atualizar-evento/{id}', 'update')->name('update-event');
});

// Events
Route::controller(EventController::class)->group(function () {
    Route::get('/evento/{id}', 'show')->name('event');
    Route::delete('/evento/{id}/apagar', 'destroy')->name('event.delete'); 
    Route::post('/evento/convidar-utilizador', 'inviteUser')->name('event.inviteUser'); 
    Route::post('/evento/{id}/aderir', 'joinEvent')->name('event.join');
    Route::delete('/evento/{id}/sair', 'leaveEvent')->name('event.leave');

    Route::delete('/api/evento/{id}/apagar', 'apiDestroy')->name('event.apiDelete');
    Route::post('/api/evento/{id}/aderir', 'apiJoinEvent')->name('event.apiJoin');
    Route::delete('/api/evento/{id}/sair', 'apiLeaveEvent')->name('event.apiLeave');
    Route::get('/api/eventos/pesquisa', 'search')->name('events.search'); 
});

// Comments
Route::controller(CommentController::class)->group(function () {
    Route::get('api/comentarios', 'index');
    Route::get('api/comentarios/{id}', 'show');
    Route::post('api/comentario/inserir', 'store')->name('comment.add');
    Route::put('api/comentario/{id}/editar', 'update');
    Route::delete('api/comentario/{id}/apagar', 'destroy');
    Route::post('api/comentario/{id}/up', 'addLike');
    Route::post('api/comentario/{id}/down', 'addDislike');
    Route::delete('api/comentario/{id}/unvote', 'removeVote');
});

// Organization
Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organizacao/{id}', 'show')->name('organization.show');
    Route::post('/organizacao/{id}/aderir', 'joinOrganization')->name('organization.join');
    Route::post('/organizacao/convidar-utilizador', 'inviteUser')->name('organization.inviteUser'); 

    Route::post('/api/organizacao/remover-utilizador', 'eliminateMember')->name('organization.eliminateMember');
    Route::delete('/api/organizacao/{id}/apagar', 'deleteOrg');
    Route::put('/api/organizacao/{id}/aprovar', 'approve')->name('organization.approve');

});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/pesquisa', 'show')->name('search');
}); 
