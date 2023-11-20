<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models\Event; 


class EventController extends Controller{
    public function show($id): View{
        return view('pages.event', [
            'user' => Auth::user(),
            'event' => Event::find($id)
        ]);
    }

    public function joinEvent($id){
        $event = Event::find($id);
        $this->authorize('join', $event);
        $event->participants()->attach(Auth::user()->id);
        $event->save();

        return redirect()->route('events', ['id' => $id]);
    }
}