<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class UserPolicy{
    public function delete(User $user){
        if(Auth::check() && (Auth::user()->is_admin || Auth::user()->id == $user->id)){
            return Response::allow();
        }
        return Response::deny('Não pode apagar este utilizador.');
    }

    public function showReports(){
        if(Auth::check() && Auth::user()->is_admin){
            return Response::allow();
        }
        return Response::deny('Apenas administradores podem ver denúncias.');
    }

    public function showAllUsers(){
        if(Auth::check() && Auth::user()->is_admin){
            return Response::allow();
        }
        return Response::deny('Apenas administradores podem ver a lista de utilizadores.');
    }

    public function showAllOrganizations(){
        if(Auth::check() && Auth::user()->is_admin){
            return Response::allow();
        }
        return Response::deny('Apenas administradores podem ver a lista de organizações.');
    }

    public function update(User $user){
        if(Auth::check() && (Auth::user()->id == $user->id)){
            return Response::allow();
        }
        return Response::deny('Não pode editar este utilizador.');
    }
}
