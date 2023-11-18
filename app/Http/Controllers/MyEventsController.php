<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MyEventController extends Controller{
    public function show(): View{
        return view('pages.myEvents', [
            'user' => Auth::user()
        ]);
    }
}