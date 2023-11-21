<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\Event;


class MyEventsController extends Controller{
    public function show(): View{
        $user = Auth::user();
        $events = Event::all();

        return view('pages.myEvents', [
            'user' => $user,
            'events' => $events,
        ]);
    }
}