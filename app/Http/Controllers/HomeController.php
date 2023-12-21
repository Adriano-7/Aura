<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


use App\Models\Event;
use App\Models\User;
use App\Models\Organization;

class HomeController extends Controller{
    public function show(): View{
        $user = Auth::user();
        
        if(!Auth::check()){
            $events = Event::where('is_public', true)->get();
        }
        else{
            $events = Event::all()->filter(function ($event) use ($user) {
                return Gate::forUser($user)->allows('show', $event);
            });
        }
        
        return view('pages.home', [
            'user' => $user,
            'events' => $events,
            'organizations' => Organization::where('approved', true)->get(),
        ]);
    }}