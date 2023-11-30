<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Organization;
use App\Models\Event;


class MyEventsController extends Controller{
    public function show(): View{
        $orgEvents = Event::join('organizers', 'events.organization_id', '=', 'organizers.organization_id')
                    ->where('organizers.user_id', Auth::user()->id)
                    ->select('events.*')
                    ->get();
        $partEvents = Event::whereHas('participants', function ($query) { $query->where('user_id', Auth::user()->id);})->get();
        return view('pages.myEvents', [
            'user' => Auth::user(),
            'orgEvents' => $orgEvents,
            'partEvents' => $partEvents
        ]);
    }
}