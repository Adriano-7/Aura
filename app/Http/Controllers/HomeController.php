<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\User;
use App\Models\Organization;

class HomeController extends Controller
{
    public function show(): View{
        return view('pages.home', [
            'user' => Auth::user(),
            'events' => Event::all(),
            'organizations' => Organization::where('approved', true)->get(),
        ]);
    }
}
