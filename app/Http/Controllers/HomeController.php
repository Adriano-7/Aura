<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class HomeController extends Controller
{
    public function show(): View{
        return view('pages.home', [
            'user' => Auth::user()
        ]);
    }
    

    /**
     * Shows all cards.
     */
    public function list()
    {
        return view('pages.home', [
            'user' => Auth::user()
        ]);
    }
}
