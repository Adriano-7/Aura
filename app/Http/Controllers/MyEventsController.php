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
        $events = Event::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('pages.myEvents', [
            'user' => $user,
            'events' => $events,
        ]);
    }
}