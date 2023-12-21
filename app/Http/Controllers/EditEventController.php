<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\AuthorizationException;


use DateTime;
class EditEventController extends Controller{
    public function show(int $id) {
        if(!is_numeric($id)){
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
        $event = Event::find($id);
        if(!$event){
            return redirect()->route('my-events')->withErrors('Event not found.');
        }
        if (Auth::user()->cannot('viewEditForm', $event)) {
            return redirect()->route('my-events')->withErrors('You are not authorized to view this event.');
        }
        return view('pages.editEvent', [
            'user' => Auth::user(),
            'event' => $event,
            'organizations' => Auth::user()->organizations()->get()
        ]);
    }


    public function update(int $id, Request $request){
        if(!is_numeric($id)){
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
        $event = Event::find($id);
        if(!$event){
            return redirect()->route('my-events')->withErrors('Event not found.');
        }

        try {
            $this->authorize('update', $event);
        } catch (AuthorizationException $e) {
            return redirect()->route('my-events')->withErrors('You are not authorized to edit this event.');
        }
    
        $validatedData = $request->validate([
            'event_name' => 'required|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
            'event_address' => 'nullable|string|max:255',
            'event_city' => 'required|string|max:255',
            'event_venue' => 'required|string|max:255',
            'organization' => 'required',
            'event_visibility' => 'required',
            'event_description' => 'required|string',
        ]);
    
        $event->name = $validatedData['event_name'];
        $start_date = $validatedData['start_date'];
        $start_time = $validatedData['start_time'];
        $start_datetime = new DateTime("{$start_date} {$start_time}");
        $event->start_date = $start_datetime;
    
        if ($validatedData['end_date'] && $validatedData['end_time']) {
            $end_date = $validatedData['end_date'];
            $end_time = $validatedData['end_time'];
            $end_datetime = new DateTime("{$end_date} {$end_time}");
            $event->end_date = $end_datetime;
    
            if ($end_datetime <= $start_datetime) {
                return redirect()->back()->withInput()->withErrors(['end_date' => 'End date and time must be after start date and time']);
            }
        } else {
            $event->end_date = null;
        }
    
        $event->address = $validatedData['event_address'];
        $event->city = $validatedData['event_city'];
        $event->venue = $validatedData['event_venue'];
        $event->organization_id = $validatedData['organization'];
        $event->is_public = $validatedData['event_visibility'] === 'public';
        $event->description = $validatedData['event_description'];
    
        $event->save();
    
        return redirect()->route('my-events')->with('status', 'Event updated successfully.');
    }
}    