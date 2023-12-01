<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\User;
use App\Models\Event;

use DateTime;
class CreateEventController extends Controller{
    public function show(): View{

        $user = Auth::user();
        $organizations = $user->userOrganizations()->get();

        return view('pages.createEvent', [
            'user' => Auth::user(),
            'organizations' => $organizations
        ]);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'event_name' => 'required|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'event_address' => 'nullable|string|max:255',
            'event_city' => 'required|string|max:255',
            'event_venue' => 'required|string|max:255',
            'organization' => 'required',
            'event_visibility' => 'required',
            'event_description' => 'required|string|max:255'
        ]);
        

        $event = new Event;
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
        } else {
            $event->end_date = null;
        }

        $event->address = $validatedData['event_address'];
        $event->city = $validatedData['event_city'];
        $event->venue = $validatedData['event_venue'];
        $event->organization_id = $validatedData['organization'];
        $event->is_public = $validatedData['event_visibility'] === 'public' ? true : false;
        $event->description = $validatedData['event_description'];
        
        $event->save();

        return redirect()->route('my-events')->with('success', 'Event created successfully');
        }
    }


