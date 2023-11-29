<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserPolicy{
    public function delete(User $user){
        return Auth::check() && (Auth::user()->is_admin || Auth::user()->id == $user->id);
    }

    public function showReports(){
        return Auth::check() && Auth::user()->is_admin;
    }

    public function showAllUsers(){
        return Auth::check() && Auth::user()->is_admin;
    }

    public function showAllOrganizations(){
        return Auth::check() && Auth::user()->is_admin;
    }
}
