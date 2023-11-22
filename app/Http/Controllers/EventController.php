<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

 use App\Models\Event; 


class EventController extends Controller{
    public function show($id): View {
        return view('pages.event', [
            'user' => Auth::user(),
            'event' => Event::find($id)
        ]);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('home')->with('status', "Evento {$event->name} removido com sucesso.");
    }

    public function joinEvent($id) {
        $event = Event::find($id);
        $this->authorize('join', $event);
        $event->participants()->attach(Auth::user()->id);
        $event->save();

        return redirect()->route('notifications')->
            with('status', "Entrou com sucesso no evento {$event->name}, {$event->venue} em {$event->start_date->format('j F, Y')}.");    
    }

}
