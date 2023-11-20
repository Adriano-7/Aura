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

    public function leaveEvent(Request $request, Event $event) {
        $user = Auth::user();
        $event->getParticipants()->detach($user->id);
        return response()->json(['status' => 'success']);
    }

    public function joinEvent(Request $request, Event $event) {
        $user = Auth::user();
        $event->getParticipants()->attach($user->id);
        return response()->json(['status' => 'success']);
    }
}