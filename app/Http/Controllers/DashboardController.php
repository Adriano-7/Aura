<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models; 


class DashboardController extends Controller{
    public function show(): View{
        return view('pages.dashboard', [
            'user' => Auth::user(),
        ]);
    }
}