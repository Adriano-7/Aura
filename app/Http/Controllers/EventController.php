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

    public function leaveEvent($id) {
        $event = Event::find($id);
        $this->authorize('leave', $event);
        $user = Auth::user();
        $event->participants()->detach($user->id);
        return response()->json(['status' => 'success']);
    }

    public function joinEvent($id) {
        $event = Event::find($id);
        $this->authorize('leave', $event);
        $user = Auth::user();
        $event->participants()->attach($user->id);
        return response()->json(['status' => 'success']);
    }
}