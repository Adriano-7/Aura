<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserPolicy{
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
