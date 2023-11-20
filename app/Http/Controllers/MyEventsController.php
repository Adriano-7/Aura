<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;


class MyEventsController extends Controller{
    public function show(): View{
        return view('pages.myEvents', [
            'user' => Auth::user(),
        ]);
    }
}