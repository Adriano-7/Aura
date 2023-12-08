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

//Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/registar', 'showRegistrationForm')->name('register');
    Route::post('/registar', 'register');
});

//User API
Route::controller(UserController::class)->group(function() {
    Route::delete('api/utilizador/{id}/apagar', 'destroy');
});

//Dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/denuncias', 'showReports')->name('dashboard.reports');
    Route::get('/dashboard/membros', 'showUsers')->name('dashboard.members');
    Route::get('/dashboard/organizacoes', 'showOrganizations')->name('dashboard.organizations');
});

// Home
Route::controller(HomeController::class)->group(function () {
    Route::get('/',  'show')->name('home');
});

//Notifications
Route::controller(NotificationsController::class)->group(function () {
    Route::get('/notificacoes', 'show')->name('notifications');

    Route::get('notificacoes/{id}/marcar-como-vista', 'markAsSeen')->name('notification.markAsSeen');

    //TODO: Colocar isto como API
    Route::delete('api/notificacoes/{id}/apagar', 'delete')->name('notification.delete');
    Route::patch('api/notificacoes/{id}/aceitar-convite', 'acceptInvitation')->name('notification.acceptInvitation');
    Route::patch('api/notificacoes/{id}/aprovar-organizacao', 'approveOrganization')->name('notification.approveOrganization');
});

//Comment Reports
Route::controller(ReportCommentController::class)->group(function () {
    Route::get('api/denuncias/comentarios', 'index');
    Route::patch('api/denuncias/comentarios/{id}/marcar-resolvido', 'markAsResolved');
});

//Event Reports
Route::controller(ReportEventController::class)->group(function () {
    Route::get('api/denuncias/evento', 'index');
    Route::patch('api/denuncias/evento/{id}/marcar-resolvido', 'markAsResolved');
});

//My Events
Route::controller(MyEventsController::class)->group(function () {
    Route::get('/meus-eventos', 'show')->name('my-events');
});

//Create Events
Route::controller(CreateEventController::class)->group(function () {
    Route::get('/criar-evento','show')->name('criar-evento');
    Route::post('/submeter-evento','store') ->name('submit-event');
});

//Edit Events
Route::controller(EditEventController::class)->group(function () {
    Route::get('/editar-evento/{id}', 'show')->name('edit-event');

    Route::put('/atualizar-evento/{id}', 'update')->name('update-event'); //TODO: Should be a patch
});

//Events
Route::controller(EventController::class)->group(function () {
    Route::get('/evento/{id}', 'show')->name('event');
    Route::delete('/evento/{id}/apagar', 'destroy')->name('event.delete'); 
    Route::post('/evento/convidar-utilizador', 'inviteUser')->name('event.inviteUser'); 

    Route::get('api/evento/{id}/aderir', 'joinEvent')->name('event.join'); //TODO: Should be a post
    Route::get('api/evento/{id}/sair', 'leaveEvent')->name('event.leave'); //TODO: Should be a delete
    Route::delete('api/evento/{id}/apagar', 'ApiDelete'); //TODO:  Needs to be refactored (We cant use both delete methods, either we use the api or php)
    Route::get('/api/eventos/pesquisa', 'search')->name('events.search'); 
});

//Comments
Route::controller(CommentController::class)->group(function () {
    Route::get('api/comentarios', 'index');
    Route::get('api/comentarios/{id}', 'show');
    Route::delete('api/comentarios/{id}/apagar', 'destroy')->name('comment.delete');
    Route::post('api/comentarios/inserir', 'store')->name('comment.add');
    Route::post('api/comentarios/{id}/addLike', 'addLike')->name('comment.addLike');
    Route::delete('api/comentarios/{id}/removeLike', 'removeLike')->name('comment.removeLike');
});

//Organization
Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organizacao/{id}', 'show')->name('organization.show');
    Route::get('/organizacao/{id}/aderir', 'joinOrganization')->name('organization.join'); //TODO: Should be a post
    Route::post('/organizacao/convidar-utilizador', 'inviteUser')->name('organization.inviteUser'); 
    Route::post('api/organizacao/remover-utilizador', 'eliminateMember')->name('organization.eliminateMember'); //TODO: Should be a api delete

    Route::delete('api/organizacao/{id}/apagar', 'deleteOrg');
});

//Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/pesquisa', 'show')->name('search');
}); 
