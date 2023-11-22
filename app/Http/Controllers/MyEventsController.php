<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Organization;
use App\Models\Event;


class MyEventsController extends Controller{

    public function participating(): View{
        $user = Auth::user();
        $events = Event::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
      

        return view('pages.myEvents', [
            'user' => $user,
            'events' => $events,
            'header' => 'Participo'
        ]);
    }

    public function organizing(): View{
        $user = Auth::user();
        $events = Event::join('organizers', 'events.organization_id', '=', 'organizers.organization_id')
        ->where('organizers.user_id', $user->id)
        ->select('events.*')
        ->get();



        return view('pages.myEvents', [
            'user' => $user,
            'events' => $events,
            'header' => 'Organizo'
        ]);
    }
}