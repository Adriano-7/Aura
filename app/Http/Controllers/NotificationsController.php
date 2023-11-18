<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class NotificationsController extends Controller{
    public function show(): View{
        return view('pages.notifications', [
            'user' => Auth::user(),
            /*'notifications' => Auth::user()->notifications*/
        ]);
    }
}