<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models\Event; 


class EventsController extends Controller{
    public function show($id): View{
        return view('pages.event', [
            'user' => Auth::user(),
            'event' => Event::find($id)
        ]);
    }
}