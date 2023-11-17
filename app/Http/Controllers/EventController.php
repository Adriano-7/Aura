<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;


class EventController extends Controller{
    public function list(){
        return view('pages.home', [
            'events' => Event::all()
        ]);
    }
}
